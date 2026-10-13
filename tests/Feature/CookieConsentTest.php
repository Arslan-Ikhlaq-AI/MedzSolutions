<?php

namespace Tests\Feature;

use App\Models\CookieConsent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_accepting_everything_is_recorded(): void
    {
        $consentId = (string) Str::uuid();

        $response = $this->postJson('/cookie-consent', $this->payload(['consent_id' => $consentId]));

        $response->assertCreated();
        $response->assertJson(['consent_id' => $consentId]);
        $this->assertDatabaseHas('cookie_consents', [
            'consent_id' => $consentId,
            'action' => 'accepted_all',
            'analytics' => true,
            'marketing' => true,
            'preferences' => true,
            'policy_version' => '1',
            'page' => '/contact-us',
        ]);
    }

    public function test_rejecting_everything_optional_is_recorded(): void
    {
        $this->postJson('/cookie-consent', $this->payload(['analytics' => false, 'marketing' => false, 'preferences' => false]))->assertCreated();

        $this->assertSame(CookieConsent::REJECTED_ALL, CookieConsent::sole()->action);
    }

    public function test_a_mixed_choice_is_recorded_as_custom(): void
    {
        $this->postJson('/cookie-consent', $this->payload(['marketing' => false]))->assertCreated();

        $consent = CookieConsent::sole();
        $this->assertSame(CookieConsent::CUSTOM, $consent->action);
        $this->assertTrue($consent->analytics);
        $this->assertFalse($consent->marketing);
    }

    public function test_a_consent_id_is_created_when_the_browser_does_not_send_one(): void
    {
        $response = $this->postJson('/cookie-consent', $this->payload(['consent_id' => null]));

        $response->assertCreated();
        $this->assertTrue(Str::isUuid($response->json('consent_id')));
        $this->assertSame($response->json('consent_id'), CookieConsent::sole()->consent_id);
    }

    public function test_changing_a_choice_keeps_the_earlier_record_as_history(): void
    {
        $consentId = (string) Str::uuid();

        $this->postJson('/cookie-consent', $this->payload(['consent_id' => $consentId]));
        $this->postJson('/cookie-consent', $this->payload(['consent_id' => $consentId, 'analytics' => false, 'marketing' => false, 'preferences' => false]));

        $this->assertSame(2, CookieConsent::count());
        $this->assertSame(['rejected_all'], CookieConsent::current()->pluck('action')->all());
    }

    public function test_no_ip_address_or_browser_details_are_stored(): void
    {
        $this->postJson('/cookie-consent', $this->payload(), ['User-Agent' => 'SecretBrowser/9'])->assertCreated();

        $this->assertSame([], array_intersect(['ip', 'ip_address', 'user_agent'], array_keys(CookieConsent::sole()->getAttributes())));
    }

    public function test_choices_must_be_true_or_false(): void
    {
        $response = $this->postJson('/cookie-consent', $this->payload(['analytics' => 'maybe']));

        $response->assertUnprocessable()->assertJsonValidationErrors('analytics');
        $this->assertDatabaseCount('cookie_consents', 0);
    }

    public function test_every_category_choice_is_required(): void
    {
        $response = $this->postJson('/cookie-consent', ['policy_version' => '1']);

        $response->assertUnprocessable()->assertJsonValidationErrors(['analytics', 'marketing', 'preferences']);
    }

    public function test_consent_id_must_be_a_uuid(): void
    {
        $this->postJson('/cookie-consent', $this->payload(['consent_id' => 'not-a-uuid']))->assertUnprocessable()->assertJsonValidationErrors('consent_id');
    }

    public function test_twenty_first_request_within_a_minute_returns_429(): void
    {
        foreach (range(1, 20) as $attempt) {
            $this->postJson('/cookie-consent', $this->payload())->assertCreated();
        }

        $this->postJson('/cookie-consent', $this->payload())->assertTooManyRequests();
    }

    public function test_every_page_shares_the_cookie_settings(): void
    {
        $response = $this->get('/about-us');

        $response->assertOk();
        $response->assertViewHas('shared', fn (array $shared): bool => $shared['cookies']['version'] === '1'
            && $shared['cookies']['days'] === 180
            && $shared['cookies']['endpoint'] === route('cookie-consent.store')
            && collect($shared['cookies']['categories'])->pluck('id')->all() === ['necessary', 'analytics', 'marketing', 'preferences']
            && $shared['routes']['cookiePolicy'] === route('cookie-policy'));
    }

    public function test_where_the_choice_was_made_and_the_privacy_signal_are_recorded(): void
    {
        $this->postJson('/cookie-consent', $this->payload(['source' => 'preferences', 'gpc' => true, 'marketing' => false]))->assertCreated();

        $consent = CookieConsent::sole();
        $this->assertSame('preferences', $consent->source);
        $this->assertTrue($consent->gpc);
    }

    public function test_unknown_source_is_rejected(): void
    {
        $this->postJson('/cookie-consent', $this->payload(['source' => 'somewhere']))->assertUnprocessable()->assertJsonValidationErrors('source');
    }

    public function test_without_tracking_tools_no_third_party_cookies_are_listed_and_no_tracking_script_is_on_the_page(): void
    {
        config()->set('services.google_analytics.measurement_id', null);
        config()->set('services.meta_pixel.id', null);

        $response = $this->get('/');

        $response->assertViewHas('shared', function (array $shared): bool {
            $categories = collect($shared['cookies']['categories'])->keyBy('id');

            return $categories['necessary']['active'] === true
                && count($categories['necessary']['cookies']) === 3
                && $categories['analytics']['active'] === true
                && array_column($categories['analytics']['cookies'], 'name') === ['Visit history']
                && $categories['marketing']['active'] === false
                && $categories['marketing']['cookies'] === [];
        });
        $response->assertSee("gtag('consent', 'default'", false);
        $response->assertDontSee('data-consent=', false);
        $response->assertDontSee('googletagmanager.com', false);
    }

    public function test_configured_google_analytics_is_blocked_until_consent_and_listed_in_the_policy(): void
    {
        config()->set('services.google_analytics.measurement_id', 'G-TEST12345');

        $response = $this->get('/');

        $response->assertSee('<script type="text/plain" data-consent="analytics" data-src="https://www.googletagmanager.com/gtag/js?id=G-TEST12345" async></script>', false);
        $response->assertSee("gtag('config', 'G-TEST12345')", false);
        $response->assertViewHas('shared', function (array $shared): bool {
            $analytics = collect($shared['cookies']['categories'])->firstWhere('id', 'analytics');

            return $analytics['active'] === true && array_column($analytics['cookies'], 'name') === ['Visit history', '_ga', '_ga_*'];
        });
    }

    public function test_configured_meta_pixel_is_blocked_until_marketing_consent(): void
    {
        config()->set('services.meta_pixel.id', '1234567890');

        $response = $this->get('/');

        $response->assertSee('<script type="text/plain" data-consent="marketing">', false);
        $response->assertSee("fbq('init', '1234567890')", false);
        $response->assertViewHas('shared', fn (array $shared): bool => collect($shared['cookies']['categories'])->firstWhere('id', 'marketing')['active'] === true);
    }

    public function test_malformed_tracking_ids_are_ignored(): void
    {
        config()->set('services.google_analytics.measurement_id', "G-1');alert(1);//");
        config()->set('services.meta_pixel.id', 'abc');

        $response = $this->get('/');

        $response->assertDontSee('data-consent=', false);
        $response->assertDontSee('alert(1)', false);
    }

    public function test_cookie_policy_page_lists_the_real_session_cookie_name(): void
    {
        config()->set('session.cookie', 'medz-session');

        $response = $this->get('/cookie-policy');

        $response->assertOk();
        $response->assertViewHas('page', 'cookie-policy');
        $response->assertViewHas('props', function (array $props): bool {
            $necessary = collect($props['categories'])->firstWhere('id', 'necessary');

            return in_array('medz-session', array_column($necessary['cookies'], 'name'), true)
                && ! in_array('{session}', array_column($necessary['cookies'], 'name'), true);
        });
    }

    public function test_admin_report_counts_each_visitors_latest_decision(): void
    {
        $returning = (string) Str::uuid();
        CookieConsent::factory()->create(['consent_id' => $returning]);
        CookieConsent::factory()->rejected()->create(['consent_id' => $returning]);
        CookieConsent::factory()->count(2)->create();
        CookieConsent::factory()->custom()->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/cookie-consents');

        $response->assertOk();
        $response->assertViewHas('stats', fn (array $stats): bool => $stats['visitors'] === 4
            && $stats['decisions'] === 5
            && $stats['accepted'] === 2
            && $stats['custom'] === 1
            && $stats['rejected'] === 1
            && $stats['analytics'] === 3
            && $stats['marketing'] === 2);
    }

    public function test_admin_report_can_be_filtered_by_decision(): void
    {
        CookieConsent::factory()->create();
        CookieConsent::factory()->rejected()->count(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/cookie-consents?action=rejected_all');

        $this->assertSame(2, $response->viewData('decisions')->total());
    }

    public function test_admin_report_finds_every_decision_of_one_visitor_by_consent_id(): void
    {
        $visitor = '3f2a9c1e-0000-4000-8000-000000000001';
        CookieConsent::factory()->create(['consent_id' => $visitor]);
        CookieConsent::factory()->rejected()->create(['consent_id' => $visitor]);
        CookieConsent::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/cookie-consents?q=3f2a9c1e');

        $this->assertSame([$visitor, $visitor], $response->viewData('decisions')->pluck('consent_id')->all());
    }

    public function test_admin_report_shows_daily_decisions_for_the_last_30_days(): void
    {
        $this->travelTo('2026-10-14 12:00:00');
        CookieConsent::factory()->count(2)->create(['created_at' => now()]);
        CookieConsent::factory()->rejected()->create(['created_at' => now()]);
        CookieConsent::factory()->custom()->create(['created_at' => now()->subDays(3)]);
        CookieConsent::factory()->create(['created_at' => now()->subDays(45)]);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/cookie-consents');

        $response->assertViewHas('trend', function (array $trend): bool {
            $days = collect($trend)->keyBy(fn (array $day): string => $day['date']->toDateString());

            return count($trend) === 30
                && $days['2026-10-14']['accepted_all'] === 2
                && $days['2026-10-14']['rejected_all'] === 1
                && $days['2026-10-14']['total'] === 3
                && $days['2026-10-11']['custom'] === 1
                && $days->sum('total') === 4;
        });
    }

    public function test_admin_can_export_the_filtered_decisions_as_csv(): void
    {
        CookieConsent::factory()->create(['consent_id' => '11111111-1111-4111-8111-111111111111', 'page' => '/about-us']);
        CookieConsent::factory()->rejected()->create(['consent_id' => '22222222-2222-4222-8222-222222222222', 'gpc' => true, 'source' => 'preferences']);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/cookie-consents/export?action=rejected_all');

        $response->assertOk();
        $response->assertDownload('cookie-consents-'.now()->format('Y-m-d').'.csv');
        $csv = $response->streamedContent();
        $this->assertStringStartsWith('"Date (UTC)","Consent ID",Decision,Analytics,Marketing,Preferences,"Global Privacy Control","Made on",Page,"Policy version"', $csv);
        $this->assertStringContainsString('22222222-2222-4222-8222-222222222222,rejected_all,no,no,no,yes,"Preference center"', $csv);
        $this->assertStringNotContainsString('11111111', $csv);
    }

    public function test_admin_report_is_closed_to_visitors_without_admin_access(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin-panel/cookie-consents')->assertForbidden();
        $this->actingAs(User::factory()->create())->get('/admin-panel/cookie-consents/export')->assertForbidden();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'consent_id' => (string) Str::uuid(),
            'analytics' => true,
            'marketing' => true,
            'preferences' => true,
            'source' => 'banner',
            'policy_version' => '1',
            'page' => '/contact-us',
            ...$overrides,
        ];
    }
}
