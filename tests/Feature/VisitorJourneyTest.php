<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\PageVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VisitorJourneyTest extends TestCase
{
    use RefreshDatabase;

    private string $visitorId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->visitorId = (string) Str::uuid();
    }

    public function test_a_page_view_is_recorded_for_a_visitor_who_allowed_analytics(): void
    {
        $response = $this->withConsent(analytics: true)->postJson('/visits', [
            'path' => '/services/denial-management',
            'title' => 'Denial Management',
            'referrer' => 'https://www.google.com/search?q=medical+billing+help',
            'utm_source' => 'newsletter',
            'utm_medium' => 'email',
            'utm_campaign' => 'october',
        ]);

        $response->assertNoContent();
        $visit = PageVisit::sole();
        $this->assertSame($this->visitorId, $visit->visitor_id);
        $this->assertSame('/services/denial-management', $visit->path);
        $this->assertSame('Denial Management', $visit->title);
        $this->assertSame('https://www.google.com/search', $visit->referrer);
        $this->assertSame('newsletter / email / october', $visit->source());
    }

    public function test_nothing_is_recorded_without_a_consent_cookie(): void
    {
        $this->postJson('/visits', ['path' => '/'])->assertNoContent();

        $this->assertDatabaseCount('page_visits', 0);
    }

    public function test_nothing_is_recorded_when_analytics_was_refused(): void
    {
        $this->withConsent(analytics: false)->postJson('/visits', ['path' => '/'])->assertNoContent();

        $this->assertDatabaseCount('page_visits', 0);
    }

    public function test_consent_given_under_an_older_policy_version_is_ignored(): void
    {
        $this->withConsent(analytics: true, version: '0')->postJson('/visits', ['path' => '/'])->assertNoContent();

        $this->assertDatabaseCount('page_visits', 0);
    }

    public function test_a_tampered_consent_cookie_is_ignored(): void
    {
        $this->withCredentials()
            ->withUnencryptedCookie('medz_consent', json_encode(['v' => '1', 'id' => 'not-a-uuid', 'a' => true]))
            ->postJson('/visits', ['path' => '/'])
            ->assertNoContent();

        $this->assertDatabaseCount('page_visits', 0);
    }

    public function test_reloading_the_same_page_within_five_minutes_is_counted_once(): void
    {
        $this->withConsent(analytics: true)->postJson('/visits', ['path' => '/about-us']);
        $this->withConsent(analytics: true)->postJson('/visits', ['path' => '/about-us']);
        $this->travel(6)->minutes();
        $this->withConsent(analytics: true)->postJson('/visits', ['path' => '/about-us']);

        $this->assertSame(2, PageVisit::count());
    }

    public function test_links_between_our_own_pages_are_not_stored_as_referrers(): void
    {
        $this->withConsent(analytics: true)->postJson('/visits', ['path' => '/faqs', 'referrer' => url('/services')]);

        $this->assertNull(PageVisit::sole()->referrer);
        $this->assertSame('Direct visit', PageVisit::sole()->source());
    }

    public function test_browsing_many_pages_never_blocks_the_contact_form(): void
    {
        foreach (range(1, 12) as $page) {
            $this->withConsent(analytics: true)->postJson('/visits', ['path' => "/page-{$page}"])->assertNoContent();
        }

        $this->from('/contact-us')->post('/contact-us', [
            '_form' => 'contact',
            'name' => 'Busy Reader',
            'email' => 'reader@example.com',
            'phone' => '+1 555 010 0111',
            'message' => 'I read everything and now I have a question.',
        ])->assertRedirect('/contact-us')->assertSessionHas('form', 'contact');

        $this->assertSame(1, ContactMessage::count());
    }

    public function test_path_must_be_a_page_on_this_website(): void
    {
        $this->withConsent(analytics: true)->postJson('/visits', ['path' => 'https://elsewhere.test/'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('path');
    }

    public function test_withdrawing_analytics_consent_deletes_the_visit_history(): void
    {
        PageVisit::factory()->count(3)->create(['visitor_id' => $this->visitorId]);
        PageVisit::factory()->create();

        $this->postJson('/cookie-consent', [
            'consent_id' => $this->visitorId,
            'analytics' => false,
            'marketing' => false,
            'preferences' => false,
            'source' => 'policy_page',
            'policy_version' => '1',
        ])->assertCreated();

        $this->assertSame(0, PageVisit::where('visitor_id', $this->visitorId)->count());
        $this->assertSame(1, PageVisit::count());
    }

    public function test_visit_history_older_than_a_year_is_pruned(): void
    {
        $old = PageVisit::factory()->create(['created_at' => now()->subYear()->subDay()]);
        $recent = PageVisit::factory()->create(['created_at' => now()->subMonths(11)]);

        $this->artisan('model:prune', ['--model' => PageVisit::class]);

        $this->assertModelMissing($old);
        $this->assertModelExists($recent);
    }

    public function test_admin_sees_what_the_visitor_read_before_contacting_us(): void
    {
        PageVisit::factory()->create(['visitor_id' => $this->visitorId, 'path' => '/', 'title' => 'Home', 'referrer' => 'https://www.bing.com/', 'created_at' => now()->subDays(3)]);
        PageVisit::factory()->create(['visitor_id' => $this->visitorId, 'path' => '/services/medical-coding', 'title' => 'Medical Coding', 'created_at' => now()->subHour()]);
        PageVisit::factory()->create(['visitor_id' => $this->visitorId, 'path' => '/blogs', 'title' => 'Read after contacting', 'created_at' => now()->addDay()]);
        $message = ContactMessage::factory()->create(['visitor_id' => $this->visitorId]);

        $response = $this->actingAs(User::factory()->admin()->create())->get("/admin-panel/contact-messages/{$message->id}");

        $response->assertOk();
        $response->assertSeeInOrder(['Visitor journey', '2 pages over 2 days', 'www.bing.com', 'Home', 'Medical Coding', 'Sent this contact message']);
        $response->assertDontSee('Read after contacting');
    }

    public function test_admin_is_told_when_there_is_no_visit_history(): void
    {
        $message = ContactMessage::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get("/admin-panel/contact-messages/{$message->id}")
            ->assertSee('No visit history for this enquiry.');
    }

    public function test_the_page_knows_where_to_send_page_views(): void
    {
        $this->get('/')->assertViewHas('shared', fn (array $shared): bool => $shared['cookies']['visitEndpoint'] === route('visits.store'));
    }

    /**
     * Browsers send cookies with same-site fetch requests; the test client needs to be told to.
     */
    private function withConsent(bool $analytics, string $version = '1'): static
    {
        return $this->withCredentials()->withUnencryptedCookie('medz_consent', json_encode([
            'v' => $version, 'id' => $this->visitorId, 'a' => $analytics, 'm' => false, 'p' => false, 'g' => 0, 't' => 1,
        ]));
    }
}
