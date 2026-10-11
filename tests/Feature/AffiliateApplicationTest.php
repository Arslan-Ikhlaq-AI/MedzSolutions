<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_valid_application_is_stored_and_confirmed(): void
    {
        $response = $this->from('/affiliate-program')->post('/affiliate-program', $this->payload());

        $response->assertRedirect('/affiliate-program');
        $response->assertSessionHas('status', 'Thank you for your interest! We will review your application and contact you within a few working days.');
        $response->assertSessionHas('form', 'affiliate');

        $this->assertDatabaseHas('affiliate_applications', [
            'name' => 'Sample Partner',
            'email' => 'partner@example.com',
            'partner_type' => 'Marketing Agencies & Advisers',
            'company' => 'Sample Consulting',
            'website' => 'https://partner.example.com',
            'audience' => 'Independent clinics in my region',
        ]);
    }

    public function test_empty_application_is_rejected_with_required_field_errors(): void
    {
        $response = $this->from('/affiliate-program')->post('/affiliate-program', []);

        $response->assertRedirect('/affiliate-program');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'partner_type' => 'Please choose the partnership type that fits you.',
        ]);

        $this->assertDatabaseCount('affiliate_applications', 0);
    }

    public function test_partner_type_that_is_not_offered_is_rejected(): void
    {
        $response = $this->from('/affiliate-program')->post('/affiliate-program', [
            ...$this->payload(),
            'partner_type' => 'Secret Handshake Society',
        ]);

        $response->assertSessionHasErrors(['partner_type' => 'The selected partner type is invalid.']);

        $this->assertDatabaseCount('affiliate_applications', 0);
    }

    public function test_website_that_is_not_a_url_is_rejected(): void
    {
        $response = $this->from('/affiliate-program')->post('/affiliate-program', [
            ...$this->payload(),
            'website' => 'not a website',
        ]);

        $response->assertSessionHasErrors(['website' => 'The website field must be a valid URL.']);

        $this->assertDatabaseCount('affiliate_applications', 0);
    }

    public function test_sixth_application_within_a_minute_returns_429(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->from('/affiliate-program')->post('/affiliate-program', $this->payload())->assertRedirect('/affiliate-program');
        }

        $response = $this->from('/affiliate-program')->post('/affiliate-program', $this->payload());

        $response->assertTooManyRequests();

        $this->assertDatabaseCount('affiliate_applications', 5);
    }

    /**
     * @return array<string, string>
     */
    private function payload(): array
    {
        return [
            '_form' => 'affiliate',
            'name' => 'Sample Partner',
            'email' => 'partner@example.com',
            'phone' => '+1 555 010 0170',
            'partner_type' => 'Marketing Agencies & Advisers',
            'company' => 'Sample Consulting',
            'website' => 'https://partner.example.com',
            'audience' => 'Independent clinics in my region',
        ];
    }
}
