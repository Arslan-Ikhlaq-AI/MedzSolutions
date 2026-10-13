<?php

namespace App\View\Composers;

use App\Support\AdminInbox;
use Illuminate\View\View;

/**
 * Shares the sidebar sections, unread counts and the notification list with the admin layout.
 */
class AdminComposer
{
    public function __construct(private AdminInbox $inbox) {}

    public function compose(View $view): void
    {
        $unread = $this->inbox->unreadCounts();

        $view->with([
            'navSections' => $this->inbox->sections(),
            'unreadCounts' => $unread,
            'unreadTotal' => array_sum($unread),
            'notifications' => $this->inbox->recent(6, unreadOnly: true),
        ]);
    }
}
