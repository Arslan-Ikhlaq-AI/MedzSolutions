<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminInbox;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function markAllAsRead(AdminInbox $inbox): RedirectResponse
    {
        $inbox->markAllAsRead();

        return back()->with('status', 'Everything is marked as read.');
    }
}
