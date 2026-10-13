<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * The cookie choice a visitor's browser sent with a request, read from the "medz_consent" cookie.
 */
class VisitorConsent
{
    public const COOKIE = 'medz_consent';

    public function __construct(
        public readonly ?string $id = null,
        public readonly bool $analytics = false,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $saved = json_decode((string) $request->cookie(self::COOKIE), true);

        if (! is_array($saved)
            || ($saved['v'] ?? null) !== (string) config('site.cookies.version')
            || ! is_string($saved['id'] ?? null)
            || ! Str::isUuid($saved['id'])) {
            return new self;
        }

        return new self($saved['id'], ($saved['a'] ?? false) === true);
    }

    /**
     * The visitor's consent ID, but only when they allowed analytics, so their visits may be linked.
     */
    public function trackableId(): ?string
    {
        return $this->analytics ? $this->id : null;
    }
}
