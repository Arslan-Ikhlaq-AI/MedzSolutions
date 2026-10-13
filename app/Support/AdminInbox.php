<?php

namespace App\Support;

use App\Models\AffiliateApplication;
use App\Models\ConsultationRequest;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * The four kinds of request visitors can send from the website, and the numbers the admin panel
 * shows about them (unread counts, recent activity and the daily chart).
 */
class AdminInbox
{
    /**
     * @return array<string, array{model: class-string<Model>, title: string, singular: string, icon: string, route: string, headline: Closure(Model): string, summary: Closure(Model): string}>
     */
    public function sections(): array
    {
        return [
            'contact-messages' => [
                'model' => ContactMessage::class,
                'title' => 'Contact messages',
                'singular' => 'Contact message',
                'icon' => 'mail',
                'route' => 'admin.contact-messages',
                'headline' => fn (Model $record): string => $record->name,
                'summary' => fn (Model $record): string => $record->subject ?: Str::limit($record->message, 70),
            ],
            'consultations' => [
                'model' => ConsultationRequest::class,
                'title' => 'Demo & review requests',
                'singular' => 'Consultation request',
                'icon' => 'calendar',
                'route' => 'admin.consultations',
                'headline' => fn (Model $record): string => $record->name,
                'summary' => fn (Model $record): string => $record->type === 'meeting'
                    ? 'Meeting request'.($record->practice_name ? ' · '.$record->practice_name : '')
                    : 'Free practice review · '.count($record->challenges ?? []).' challenge(s)',
            ],
            'job-applications' => [
                'model' => JobApplication::class,
                'title' => 'Job applications',
                'singular' => 'Job application',
                'icon' => 'briefcase',
                'route' => 'admin.job-applications',
                'headline' => fn (Model $record): string => $record->name,
                'summary' => fn (Model $record): string => 'Applied for '.$record->job_title,
            ],
            'affiliates' => [
                'model' => AffiliateApplication::class,
                'title' => 'Affiliate applications',
                'singular' => 'Affiliate application',
                'icon' => 'share',
                'route' => 'admin.affiliates',
                'headline' => fn (Model $record): string => $record->name,
                'summary' => fn (Model $record): string => ($record->partner_type ?: 'Partner').($record->company ? ' · '.$record->company : ''),
            ],
        ];
    }

    /**
     * @return array{model: class-string<Model>, title: string, singular: string, icon: string, route: string, headline: Closure(Model): string, summary: Closure(Model): string}
     */
    public function section(string $key): array
    {
        return $this->sections()[$key];
    }

    /**
     * Unread request counts by section key.
     *
     * @return array<string, int>
     */
    public function unreadCounts(): array
    {
        return collect($this->sections())
            ->map(fn (array $section): int => $section['model']::unread()->count())
            ->all();
    }

    /**
     * The most recent requests across every section, newest first.
     *
     * @return Collection<int, array{key: string, section: string, icon: string, headline: string, summary: string, created_at: Carbon, unread: bool, url: string}>
     */
    public function recent(int $limit = 8, bool $unreadOnly = false): Collection
    {
        return collect($this->sections())
            ->flatMap(fn (array $section, string $key): Collection => $section['model']::query()
                ->when($unreadOnly, fn ($query) => $query->unread())
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn (Model $record): array => [
                    'key' => $key,
                    'section' => $section['singular'],
                    'icon' => $section['icon'],
                    'headline' => ($section['headline'])($record),
                    'summary' => ($section['summary'])($record),
                    'created_at' => $record->created_at,
                    'unread' => $record->isUnread(),
                    'url' => route($section['route'].'.show', $record->getKey()),
                ]))
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    /**
     * Requests received per day for the last $days days, oldest first.
     *
     * @return array<int, array{date: Carbon, count: int}>
     */
    public function dailyCounts(int $days = 14): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $perDay = collect($this->sections())
            ->flatMap(fn (array $section): Collection => $section['model']::query()->where('created_at', '>=', $start)->pluck('created_at'))
            ->countBy(fn (Carbon $date): string => $date->toDateString());

        return collect(range(0, $days - 1))
            ->map(fn (int $offset): array => [
                'date' => $start->copy()->addDays($offset),
                'count' => (int) $perDay->get($start->copy()->addDays($offset)->toDateString(), 0),
            ])
            ->all();
    }

    public function markAllAsRead(): void
    {
        foreach ($this->sections() as $section) {
            $section['model']::unread()->update(['read_at' => now()]);
        }
    }
}
