<?php

namespace App\Support;

use Illuminate\Support\Arr;

/**
 * The cookies this website can set, grouped by consent category, and the optional tracking
 * tools behind them. Read from config/site.php ("cookies") and config/services.php.
 */
class CookieCatalog
{
    /**
     * Configured tracking tools keyed by integration name. Malformed IDs are ignored so a typo
     * in the environment file can never inject anything into the page.
     *
     * @return array<string, string>
     */
    public function integrations(): array
    {
        $configured = [
            'google_analytics' => config('services.google_analytics.measurement_id'),
            'meta_pixel' => config('services.meta_pixel.id'),
        ];

        $patterns = [
            'google_analytics' => '/^G-[A-Z0-9]{4,20}$/',
            'meta_pixel' => '/^\d{6,20}$/',
        ];

        return array_filter(
            $configured,
            fn (?string $id, string $name): bool => filled($id) && preg_match($patterns[$name], $id) === 1,
            ARRAY_FILTER_USE_BOTH,
        );
    }

    /**
     * Consent categories with only the cookies that can actually be set right now.
     *
     * @return array<int, array{id: string, title: string, required: bool, active: bool, description: string, cookies: array<int, array{name: string, provider: string, purpose: string, duration: string}>}>
     */
    public function categories(): array
    {
        $integrations = $this->integrations();

        return collect(config('site.cookies.categories'))
            ->map(function (array $category) use ($integrations): array {
                $cookies = collect($category['cookies'])
                    ->filter(fn (array $cookie): bool => ! isset($cookie['integration']) || isset($integrations[$cookie['integration']]))
                    ->map(fn (array $cookie): array => [
                        ...Arr::only($cookie, ['provider', 'purpose', 'duration']),
                        'name' => str_replace('{session}', config('session.cookie'), $cookie['name']),
                    ])
                    ->values()
                    ->all();

                return [
                    'id' => $category['id'],
                    'title' => $category['title'],
                    'required' => $category['required'],
                    'active' => $category['required'] || $cookies !== [],
                    'description' => $category['description'],
                    'cookies' => $cookies,
                ];
            })
            ->all();
    }

    /**
     * Everything the cookie banner and preference center need in the browser.
     *
     * @return array<string, mixed>
     */
    public function settings(): array
    {
        return [
            'version' => (string) config('site.cookies.version'),
            'days' => (int) config('site.cookies.days'),
            'intro' => config('site.cookies.intro'),
            'categories' => $this->categories(),
            'endpoint' => route('cookie-consent.store'),
            'visitEndpoint' => route('visits.store'),
        ];
    }
}
