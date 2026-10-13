<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CookieConsent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CookieConsentController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const ACTIONS = [CookieConsent::ACCEPTED_ALL, CookieConsent::CUSTOM, CookieConsent::REJECTED_ALL];

    public function index(Request $request): View
    {
        $current = CookieConsent::current();
        $byAction = (clone $current)->selectRaw('action, count(*) as total')->groupBy('action')->pluck('total', 'action');

        $trend = $this->trend(30);

        return view('admin.cookie-consents.index', [
            'decisions' => $this->filtered($request)->latest()->latest('id')->paginate(20)->withQueryString(),
            'filters' => [
                'action' => $request->query('action'),
                'q' => $request->string('q')->trim()->value(),
            ],
            'stats' => [
                'visitors' => (clone $current)->count(),
                'decisions' => CookieConsent::count(),
                'accepted' => (int) $byAction->get(CookieConsent::ACCEPTED_ALL, 0),
                'custom' => (int) $byAction->get(CookieConsent::CUSTOM, 0),
                'rejected' => (int) $byAction->get(CookieConsent::REJECTED_ALL, 0),
                'analytics' => (clone $current)->where('analytics', true)->count(),
                'marketing' => (clone $current)->where('marketing', true)->count(),
                'preferences' => (clone $current)->where('preferences', true)->count(),
                'gpc' => (clone $current)->where('gpc', true)->count(),
            ],
            'trend' => $trend,
            'trendMax' => max(1, collect($trend)->max('total')),
            'sources' => CookieConsent::SOURCES,
            'policyVersion' => config('site.cookies.version'),
        ]);
    }

    /**
     * Downloads the decisions matching the current filters as a CSV file, for audits and
     * proof-of-consent requests.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request);

        return response()->streamDownload(function () use ($query): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Date (UTC)', 'Consent ID', 'Decision', 'Analytics', 'Marketing', 'Preferences', 'Global Privacy Control', 'Made on', 'Page', 'Policy version']);

            $query->lazyById(500)->each(function (CookieConsent $consent) use ($output): void {
                fputcsv($output, [
                    $consent->created_at->utc()->toDateTimeString(),
                    $consent->consent_id,
                    $consent->action,
                    $consent->analytics ? 'yes' : 'no',
                    $consent->marketing ? 'yes' : 'no',
                    $consent->preferences ? 'yes' : 'no',
                    $consent->gpc ? 'yes' : 'no',
                    CookieConsent::SOURCES[$consent->source] ?? $consent->source,
                    $consent->page,
                    $consent->policy_version,
                ]);
            });

            fclose($output);
        }, 'cookie-consents-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return Builder<CookieConsent>
     */
    private function filtered(Request $request): Builder
    {
        $action = $request->query('action');
        $search = $request->string('q')->trim()->value();

        return CookieConsent::query()
            ->when(in_array($action, self::ACTIONS, true), fn (Builder $query) => $query->where('action', $action))
            ->when($search !== '', fn (Builder $query) => $query->where('consent_id', 'like', addcslashes($search, '%_\\').'%'));
    }

    /**
     * Decisions per day, split by outcome, oldest day first.
     *
     * @return array<int, array{date: Carbon, total: int, accepted_all: int, custom: int, rejected_all: int}>
     */
    private function trend(int $days): array
    {
        $start = now()->startOfDay()->subDays($days - 1);

        $rows = CookieConsent::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('date(created_at) as day, action, count(*) as total')
            ->groupBy('day', 'action')
            ->get()
            ->groupBy('day');

        return collect(range(0, $days - 1))->map(function (int $offset) use ($start, $rows): array {
            $date = $start->copy()->addDays($offset);
            $counts = $rows->get($date->toDateString(), collect())->pluck('total', 'action');

            $day = ['date' => $date, 'total' => 0];

            foreach (self::ACTIONS as $action) {
                $day[$action] = (int) $counts->get($action, 0);
                $day['total'] += $day[$action];
            }

            return $day;
        })->all();
    }
}
