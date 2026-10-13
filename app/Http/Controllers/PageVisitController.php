<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePageVisitRequest;
use App\Models\PageVisit;
use App\Support\VisitorConsent;
use Illuminate\Http\Response;

class PageVisitController extends Controller
{
    /**
     * Records a page view for visitors who allowed analytics cookies. Anything else is ignored.
     */
    public function store(StorePageVisitRequest $request): Response
    {
        $visitorId = VisitorConsent::fromRequest($request)->trackableId();

        if (! $visitorId) {
            return response()->noContent();
        }

        $path = $request->validated('path');

        $recentlyRecorded = PageVisit::query()
            ->where('visitor_id', $visitorId)
            ->where('path', $path)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if (! $recentlyRecorded) {
            PageVisit::create([
                ...$request->safe()->only(['title', 'utm_source', 'utm_medium', 'utm_campaign']),
                'visitor_id' => $visitorId,
                'path' => $path,
                'referrer' => $this->externalReferrer($request->validated('referrer'), $request->getHost()),
                'created_at' => now(),
            ]);
        }

        return response()->noContent();
    }

    /**
     * Keeps only where the visitor came from (scheme, host and path), never query strings, and
     * ignores links between pages of this website.
     */
    private function externalReferrer(?string $referrer, string $ownHost): ?string
    {
        $parts = $referrer ? parse_url($referrer) : null;

        if (! $parts || ! isset($parts['host']) || $parts['host'] === $ownHost) {
            return null;
        }

        return substr($parts['scheme'].'://'.$parts['host'].($parts['path'] ?? ''), 0, 255);
    }
}
