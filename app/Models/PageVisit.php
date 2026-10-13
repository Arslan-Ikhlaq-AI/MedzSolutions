<?php

namespace App\Models;

use Database\Factories\PageVisitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * A page opened by a visitor who allowed analytics cookies. Visitors are known only by their
 * random consent ID until they choose to contact us through a form.
 */
#[Fillable(['visitor_id', 'path', 'title', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'created_at'])]
class PageVisit extends Model
{
    /** @use HasFactory<PageVisitFactory> */
    use HasFactory, Prunable;

    public const UPDATED_AT = null;

    /**
     * Visit history is kept for twelve months.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subYear());
    }

    /**
     * Where the visitor came from: a campaign, another website or direct.
     */
    public function source(): string
    {
        if ($this->utm_source) {
            return collect([$this->utm_source, $this->utm_medium, $this->utm_campaign])->filter()->implode(' / ');
        }

        if ($this->referrer) {
            return parse_url($this->referrer, PHP_URL_HOST) ?: $this->referrer;
        }

        return 'Direct visit';
    }
}
