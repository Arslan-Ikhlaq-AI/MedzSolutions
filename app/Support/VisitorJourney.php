<?php

namespace App\Support;

use App\Models\PageVisit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * What a visitor looked at on the website before they contacted us.
 */
class VisitorJourney
{
    /**
     * @return array{first_seen: Carbon, source: string, page_count: int, visit_days: int, pages: Collection<int, PageVisit>}|null
     */
    public function before(?string $visitorId, Carbon $until, int $limit = 40): ?array
    {
        if (! $visitorId) {
            return null;
        }

        $visits = PageVisit::query()
            ->where('visitor_id', $visitorId)
            ->where('created_at', '<=', $until->copy()->addMinute())
            ->oldest('created_at')
            ->oldest('id')
            ->get();

        if ($visits->isEmpty()) {
            return null;
        }

        $first = $visits->first();

        return [
            'first_seen' => $first->created_at,
            'source' => $visits->first(fn (PageVisit $visit): bool => $visit->utm_source || $visit->referrer)?->source() ?? $first->source(),
            'page_count' => $visits->count(),
            'visit_days' => $visits->groupBy(fn (PageVisit $visit): string => $visit->created_at->toDateString())->count(),
            'pages' => $visits->take(-$limit)->values(),
        ];
    }
}
