<?php

namespace App\Models;

use Database\Factories\CookieConsentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One cookie decision made by a visitor. Decisions are never edited: a visitor who changes their
 * mind creates a new row with the same consent_id, so the full history is kept as proof of consent.
 * No IP address or browser details are stored. "gpc" notes that the browser sent a Global
 * Privacy Control signal when the choice was made.
 */
#[Fillable(['consent_id', 'action', 'source', 'analytics', 'marketing', 'preferences', 'gpc', 'policy_version', 'page'])]
class CookieConsent extends Model
{
    /** @use HasFactory<CookieConsentFactory> */
    use HasFactory;

    public const ACCEPTED_ALL = 'accepted_all';

    public const REJECTED_ALL = 'rejected_all';

    public const CUSTOM = 'custom';

    /**
     * Where on the website the visitor made the decision.
     *
     * @var array<string, string>
     */
    public const SOURCES = [
        'banner' => 'Banner',
        'preferences' => 'Preference center',
        'policy_page' => 'Cookie Policy page',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'preferences' => 'boolean',
            'gpc' => 'boolean',
        ];
    }

    /**
     * Names the overall decision from the three optional categories.
     */
    public static function actionFor(bool $analytics, bool $marketing, bool $preferences): string
    {
        return match (true) {
            $analytics && $marketing && $preferences => self::ACCEPTED_ALL,
            ! $analytics && ! $marketing && ! $preferences => self::REJECTED_ALL,
            default => self::CUSTOM,
        };
    }

    /**
     * Only each visitor's most recent decision, which is the one currently in force.
     *
     * @param  Builder<static>  $query
     */
    public function scopeCurrent(Builder $query): void
    {
        $query->whereIn('id', static::query()->selectRaw('max(id)')->groupBy('consent_id'));
    }
}
