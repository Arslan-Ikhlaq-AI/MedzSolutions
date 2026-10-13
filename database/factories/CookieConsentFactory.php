<?php

namespace Database\Factories;

use App\Models\CookieConsent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CookieConsent>
 */
class CookieConsentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'consent_id' => (string) Str::uuid(),
            'action' => CookieConsent::ACCEPTED_ALL,
            'source' => 'banner',
            'analytics' => true,
            'marketing' => true,
            'preferences' => true,
            'gpc' => false,
            'policy_version' => '1',
            'page' => '/',
        ];
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => CookieConsent::REJECTED_ALL,
            'analytics' => false,
            'marketing' => false,
            'preferences' => false,
        ]);
    }

    public function custom(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => CookieConsent::CUSTOM,
            'analytics' => true,
            'marketing' => false,
            'preferences' => false,
        ]);
    }
}
