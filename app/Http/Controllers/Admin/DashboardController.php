<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\AdminInbox;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(AdminInbox $inbox): View
    {
        $sections = collect($inbox->sections())->map(fn (array $section): array => [
            ...$section,
            'total' => $section['model']::count(),
            'unread' => $section['model']::unread()->count(),
            'this_week' => $section['model']::where('created_at', '>=', now()->subDays(7))->count(),
        ]);

        $daily = $inbox->dailyCounts(14);

        return view('admin.dashboard', [
            'sections' => $sections,
            'recent' => $inbox->recent(10),
            'daily' => $daily,
            'dailyMax' => max(1, collect($daily)->max('count')),
            'weekTotal' => $sections->sum('this_week'),
            'unreadTotal' => $sections->sum('unread'),
            'posts' => [
                'published' => Post::published()->count(),
                'drafts' => Post::where('status', Post::STATUS_DRAFT)->count(),
            ],
        ]);
    }
}
