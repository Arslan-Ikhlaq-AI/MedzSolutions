<?php

namespace App\Models\Concerns;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for records submitted through the public website and handled in the admin panel.
 */
trait HasInboxStatus
{
    public function initializeHasInboxStatus(): void
    {
        $this->mergeCasts([
            'status' => RequestStatus::class,
            'read_at' => 'datetime',
            'follow_up_sent_at' => 'datetime',
        ]);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }
}
