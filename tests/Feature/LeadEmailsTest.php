<?php

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionFollowUp;
use App\Mail\SubmissionReceived;
use App\Models\AffiliateApplication;
use App\Models\ConsultationRequest;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\PageVisit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class LeadEmailsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->withoutDefer();
        Mail::fake();
        Storage::fake('local');
        $this->travelTo('2026-10-12 09:00:00');

        config()->set('site.emails.notify', 'team@medz.test');
        config()->set('site.emails.reply_to', 'team@medz.test');
        config()->set('site.jobs', [[
            'slug' => 'medical-coder',
            'title' => 'Certified Medical Coder',
            'department' => 'Coding',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Code outpatient encounters.',
            'body' => 'Role description.',
        ]]);
    }

    /**
     * @return array<string, array{0: string, 1: array<string, mixed>, 2: string, 3: string}>
     */
    public static function forms(): array
    {
        return [
            'contact' => ['/contact-us', [
                '_form' => 'contact', 'name' => 'Sample Visitor', 'email' => 'visitor@example.com', 'phone' => '+1 555 010 0199',
                'subject' => 'Billing question', 'message' => 'We would like to discuss our billing.',
            ], 'We received your message', 'Contact message'],
            'meeting' => ['/consultations', [
                '_form' => 'meeting', 'type' => 'meeting', 'name' => 'Sample Doctor', 'email' => 'visitor@example.com', 'phone' => '+1 555 010 0100',
                'practice_name' => 'Sample Family Clinic', 'specialty' => 'Cardiology', 'preferred_date' => '2026-10-20',
            ], 'Your meeting request is confirmed', 'Meeting request'],
            'review' => ['/consultations', [
                '_form' => 'review', 'type' => 'review', 'name' => 'Sample Doctor', 'email' => 'visitor@example.com', 'phone' => '+1 555 010 0100',
                'challenges' => ['denials'],
            ], 'Your free practice review is on its way', 'Free practice review request'],
            'job application' => ['/careers/medical-coder/apply', [
                '_form' => 'application', 'name' => 'Sample Applicant', 'email' => 'visitor@example.com', 'phone' => '+1 555 010 0150',
                'cover_letter' => 'I have five years of coding experience.',
            ], 'We received your application', 'Job application'],
            'affiliate' => ['/affiliate-program', [
                '_form' => 'affiliate', 'name' => 'Sample Partner', 'email' => 'visitor@example.com', 'phone' => '+1 555 010 0170',
                'partner_type' => 'Marketing Agencies & Advisers', 'company' => 'Sample Consulting', 'audience' => 'Independent clinics',
            ], 'Thanks for applying to our affiliate program', 'Affiliate application'],
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    #[DataProvider('forms')]
    public function test_the_visitor_gets_a_confirmation_and_the_team_gets_an_alert(string $uri, array $payload, string $subject, string $label): void
    {
        $this->from('/')->post($uri, $this->withResume($uri, $payload))->assertRedirect('/');

        Mail::assertSent(SubmissionReceived::class, fn (SubmissionReceived $mail): bool => $mail->hasTo('visitor@example.com')
            && $mail->hasSubject($subject)
            && $mail->hasReplyTo('team@medz.test'));
        Mail::assertSent(NewSubmissionAlert::class, fn (NewSubmissionAlert $mail): bool => $mail->hasTo('team@medz.test')
            && $mail->hasSubject("New {$label}: {$payload['name']}")
            && $mail->hasReplyTo('visitor@example.com'));
        Mail::assertSentCount(2);
    }

    public function test_confirmation_greets_the_visitor_and_repeats_what_they_asked_about(): void
    {
        $this->post('/contact-us', self::forms()['contact'][1]);

        Mail::assertSent(SubmissionReceived::class, function (SubmissionReceived $mail): bool {
            $mail->assertSeeInHtml('Hi Sample,');
            $mail->assertSeeInHtml('A billing specialist has your message');
            $mail->assertSeeInHtml('Subject: Billing question');
            $mail->assertSeeInHtml(config('site.company.phone'));

            return true;
        });
    }

    public function test_alert_shows_what_the_visitor_read_when_they_allowed_analytics(): void
    {
        $visitorId = (string) Str::uuid();
        PageVisit::factory()->create(['visitor_id' => $visitorId, 'path' => '/', 'title' => 'Home', 'referrer' => 'https://www.google.com/', 'created_at' => now()->subDays(2)]);
        PageVisit::factory()->create(['visitor_id' => $visitorId, 'path' => '/services/denial-management', 'title' => 'Denial Management', 'created_at' => now()->subMinutes(10)]);

        $this->withUnencryptedCookie('medz_consent', $this->consentCookie($visitorId, analytics: true))
            ->post('/contact-us', self::forms()['contact'][1]);

        $this->assertSame($visitorId, ContactMessage::sole()->visitor_id);
        Mail::assertSent(NewSubmissionAlert::class, function (NewSubmissionAlert $mail): bool {
            $mail->assertSeeInHtml('www.google.com');
            $mail->assertSeeInHtml('Denial Management');

            return $mail->journey['page_count'] === 2 && $mail->journey['visit_days'] === 2;
        });
    }

    public function test_without_analytics_consent_nothing_is_linked_and_the_alert_says_so(): void
    {
        $visitorId = (string) Str::uuid();
        PageVisit::factory()->create(['visitor_id' => $visitorId]);

        $this->withUnencryptedCookie('medz_consent', $this->consentCookie($visitorId, analytics: false))
            ->post('/contact-us', self::forms()['contact'][1]);

        $this->assertNull(ContactMessage::sole()->visitor_id);
        Mail::assertSent(NewSubmissionAlert::class, function (NewSubmissionAlert $mail): bool {
            $mail->assertSeeInHtml('No visit history');

            return $mail->journey === null;
        });
    }

    public function test_no_team_alert_is_sent_when_no_address_is_configured(): void
    {
        config()->set('site.emails.notify', null);

        $this->post('/contact-us', self::forms()['contact'][1]);

        Mail::assertSent(SubmissionReceived::class);
        Mail::assertNotSent(NewSubmissionAlert::class);
    }

    public function test_a_mail_server_failure_never_breaks_the_form(): void
    {
        Exceptions::fake();
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP is down'));

        $response = $this->from('/contact-us')->post('/contact-us', self::forms()['contact'][1]);

        $response->assertRedirect('/contact-us');
        $response->assertSessionHas('form', 'contact');
        $this->assertSame(1, ContactMessage::count());
        Exceptions::assertReported(fn (RuntimeException $exception): bool => $exception->getMessage() === 'SMTP is down');
    }

    public function test_follow_up_goes_to_enquiries_still_new_after_two_days(): void
    {
        $contact = ContactMessage::factory()->create(['email' => 'waiting@example.com', 'created_at' => now()->subDays(3)]);
        $review = ConsultationRequest::factory()->review()->create(['email' => 'review@example.com', 'created_at' => now()->subDays(2)->subHour()]);

        $this->artisan('leads:send-follow-ups')->expectsOutput('Sent 2 follow-up emails.')->assertSuccessful();

        Mail::assertSent(SubmissionFollowUp::class, fn (SubmissionFollowUp $mail): bool => $mail->hasTo('waiting@example.com') && $mail->hasReplyTo('team@medz.test'));
        Mail::assertSent(SubmissionFollowUp::class, fn (SubmissionFollowUp $mail): bool => $mail->hasTo('review@example.com'));
        $this->assertNotNull($contact->fresh()->follow_up_sent_at);
        $this->assertNotNull($review->fresh()->follow_up_sent_at);
    }

    public function test_follow_up_is_skipped_for_recent_handled_old_or_already_followed_up_enquiries(): void
    {
        ContactMessage::factory()->create(['created_at' => now()->subDay()]);
        ContactMessage::factory()->create(['status' => RequestStatus::InProgress, 'created_at' => now()->subDays(3)]);
        ContactMessage::factory()->create(['status' => RequestStatus::Closed, 'created_at' => now()->subDays(3)]);
        ContactMessage::factory()->create(['follow_up_sent_at' => now()->subDay(), 'created_at' => now()->subDays(3)]);
        ContactMessage::factory()->create(['created_at' => now()->subDays(20)]);
        JobApplication::factory()->create(['created_at' => now()->subDays(3)]);
        AffiliateApplication::factory()->create(['created_at' => now()->subDays(3)]);

        $this->artisan('leads:send-follow-ups')->expectsOutput('Sent 0 follow-up emails.');

        Mail::assertNothingSent();
    }

    public function test_follow_up_is_only_sent_once(): void
    {
        ContactMessage::factory()->create(['created_at' => now()->subDays(3)]);

        $this->artisan('leads:send-follow-ups');
        $this->artisan('leads:send-follow-ups')->expectsOutput('Sent 0 follow-up emails.');

        Mail::assertSentCount(1);
    }

    public function test_follow_ups_and_visit_clean_up_are_scheduled(): void
    {
        $commands = collect(app(Schedule::class)->events())->map(fn ($event): string => $event->command);

        $this->assertTrue($commands->contains(fn (string $command): bool => str_contains($command, 'leads:send-follow-ups')));
        $this->assertTrue($commands->contains(fn (string $command): bool => str_contains($command, 'model:prune')));
    }

    private function consentCookie(string $visitorId, bool $analytics): string
    {
        return json_encode(['v' => '1', 'id' => $visitorId, 'a' => $analytics, 'm' => false, 'p' => false, 'g' => 0, 't' => 1]);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withResume(string $uri, array $payload): array
    {
        return str_contains($uri, 'careers')
            ? [...$payload, 'resume' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf')]
            : $payload;
    }
}
