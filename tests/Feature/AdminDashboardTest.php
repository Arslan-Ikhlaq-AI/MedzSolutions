<?php

namespace Tests\Feature;

use App\Models\AffiliateApplication;
use App\Models\ConsultationRequest;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_dashboard_counts_every_kind_of_request_with_its_unread_total(): void
    {
        ContactMessage::factory()->count(2)->create();
        ContactMessage::factory()->create(['read_at' => now()]);
        ConsultationRequest::factory()->create();
        ConsultationRequest::factory()->review()->create(['read_at' => now()]);
        JobApplication::factory()->create();
        AffiliateApplication::factory()->count(4)->create(['read_at' => now()]);

        $response = $this->actingAs($this->admin)->get('/admin-panel');

        $response->assertOk();
        $response->assertViewHas('sections', fn ($sections): bool => $sections['contact-messages']['total'] === 3
            && $sections['contact-messages']['unread'] === 2
            && $sections['consultations']['total'] === 2
            && $sections['consultations']['unread'] === 1
            && $sections['job-applications']['unread'] === 1
            && $sections['affiliates']['total'] === 4
            && $sections['affiliates']['unread'] === 0);
        $response->assertViewHas('unreadTotal', 4);
    }

    public function test_recent_activity_lists_the_newest_requests_from_all_sections_first(): void
    {
        ContactMessage::factory()->create(['name' => 'Older Sender', 'created_at' => now()->subDays(3)]);
        JobApplication::factory()->create(['name' => 'Newest Applicant', 'job_title' => 'Medical Coder', 'created_at' => now()->subHour()]);
        AffiliateApplication::factory()->create(['name' => 'Middle Partner', 'created_at' => now()->subDay()]);

        $response = $this->actingAs($this->admin)->get('/admin-panel');

        $response->assertViewHas('recent', fn ($recent): bool => $recent->pluck('headline')->all() === ['Newest Applicant', 'Middle Partner', 'Older Sender']);
        $response->assertSee('Applied for Medical Coder');
    }

    public function test_dashboard_shows_blog_totals(): void
    {
        Post::factory()->count(2)->create();
        Post::factory()->draft()->create();

        $response = $this->actingAs($this->admin)->get('/admin-panel');

        $response->assertViewHas('posts', ['published' => 2, 'drafts' => 1]);
    }

    public function test_chart_groups_requests_by_day(): void
    {
        $this->travelTo('2026-10-14 12:00:00');
        ContactMessage::factory()->count(2)->create(['created_at' => now()]);
        JobApplication::factory()->create(['created_at' => now()]);
        AffiliateApplication::factory()->create(['created_at' => now()->subDays(2)]);
        ContactMessage::factory()->create(['created_at' => now()->subDays(30)]);

        $response = $this->actingAs($this->admin)->get('/admin-panel');

        $response->assertViewHas('daily', function (array $daily): bool {
            $counts = collect($daily)->mapWithKeys(fn (array $day): array => [$day['date']->toDateString() => $day['count']]);

            return count($daily) === 14
                && $counts['2026-10-14'] === 3
                && $counts['2026-10-12'] === 1
                && $counts->sum() === 4;
        });
    }

    public function test_mark_all_as_read_clears_every_unread_request(): void
    {
        ContactMessage::factory()->create();
        ConsultationRequest::factory()->create();
        JobApplication::factory()->create();
        AffiliateApplication::factory()->create();

        $response = $this->actingAs($this->admin)->from('/admin-panel')->post('/admin-panel/notifications/read-all');

        $response->assertRedirect('/admin-panel');
        $this->assertSame(0, ContactMessage::unread()->count() + ConsultationRequest::unread()->count() + JobApplication::unread()->count() + AffiliateApplication::unread()->count());
    }

    public function test_notification_bell_lists_unread_requests_only(): void
    {
        ContactMessage::factory()->create(['name' => 'Unread Visitor']);
        ContactMessage::factory()->create(['name' => 'Already Read Visitor', 'read_at' => now()]);

        $response = $this->actingAs($this->admin)->get('/admin-panel/posts');

        $response->assertSee('Unread Visitor');
        $response->assertDontSee('Already Read Visitor');
    }
}
