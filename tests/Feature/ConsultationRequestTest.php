<?php

namespace Tests\Feature;

use App\Models\ConsultationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->travelTo('2026-10-12 09:00:00');
    }

    public function test_meeting_request_is_stored_and_confirmed(): void
    {
        $response = $this->from('/')->post('/consultations', [
            ...$this->meetingPayload(),
            'challenges' => ['denials'],
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'Thank you! Your meeting request has been received. We will contact you shortly to confirm a time.');
        $response->assertSessionHas('form', 'meeting');

        $consultation = ConsultationRequest::sole();
        $this->assertSame('meeting', $consultation->type);
        $this->assertSame('dr.sample@example.com', $consultation->email);
        $this->assertSame('Sample Family Clinic', $consultation->practice_name);
        $this->assertSame('Cardiology', $consultation->specialty);
        $this->assertSame('2026-10-20', $consultation->preferred_date->toDateString());
        $this->assertNull($consultation->challenges);
    }

    public function test_practice_review_stores_the_selected_challenges_without_meeting_details(): void
    {
        $response = $this->from('/')->post('/consultations', [
            '_form' => 'review',
            'type' => 'review',
            'name' => 'Dr. Sample',
            'email' => 'dr.sample@example.com',
            'phone' => '+1 555 010 0100',
            'practice_name' => 'Ignored Clinic',
            'preferred_date' => '2026-10-20',
            'challenges' => ['denials', 'coding'],
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'Thank you! A billing specialist will review your answers and contact you within one working day.');
        $response->assertSessionHas('form', 'review');

        $consultation = ConsultationRequest::sole();
        $this->assertSame('review', $consultation->type);
        $this->assertSame(['denials', 'coding'], $consultation->challenges);
        $this->assertNull($consultation->practice_name);
        $this->assertNull($consultation->preferred_date);
    }

    public function test_empty_request_is_rejected_with_required_field_errors(): void
    {
        $response = $this->from('/')->post('/consultations', []);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors([
            'type' => 'The type field is required.',
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
        ]);

        $this->assertDatabaseCount('consultation_requests', 0);
    }

    public function test_meeting_request_rejects_a_date_in_the_past(): void
    {
        $response = $this->from('/')->post('/consultations', [
            ...$this->meetingPayload(),
            'preferred_date' => '2026-10-11',
        ]);

        $response->assertSessionHasErrors(['preferred_date' => 'The preferred date field must be a date after or equal to today.']);

        $this->assertDatabaseCount('consultation_requests', 0);
    }

    public function test_meeting_request_rejects_a_specialty_that_is_not_listed(): void
    {
        $response = $this->from('/')->post('/consultations', [
            ...$this->meetingPayload(),
            'specialty' => 'Astrology',
        ]);

        $response->assertSessionHasErrors(['specialty' => 'The selected specialty is invalid.']);

        $this->assertDatabaseCount('consultation_requests', 0);
    }

    public function test_practice_review_requires_at_least_one_challenge(): void
    {
        $response = $this->from('/')->post('/consultations', [
            'type' => 'review',
            'name' => 'Dr. Sample',
            'email' => 'dr.sample@example.com',
        ]);

        $response->assertSessionHasErrors(['challenges' => 'Please choose at least one challenge.']);

        $this->assertDatabaseCount('consultation_requests', 0);
    }

    public function test_practice_review_rejects_unknown_challenges(): void
    {
        $response = $this->from('/')->post('/consultations', [
            'type' => 'review',
            'name' => 'Dr. Sample',
            'email' => 'dr.sample@example.com',
            'challenges' => ['denials', 'free-money'],
        ]);

        $response->assertSessionHasErrors(['challenges.1' => 'The selected challenges.1 is invalid.']);

        $this->assertDatabaseCount('consultation_requests', 0);
    }

    public function test_sixth_request_within_a_minute_returns_429(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->from('/')->post('/consultations', $this->meetingPayload())->assertRedirect('/');
        }

        $response = $this->from('/')->post('/consultations', $this->meetingPayload());

        $response->assertTooManyRequests();

        $this->assertDatabaseCount('consultation_requests', 5);
    }

    /**
     * @return array<string, string>
     */
    private function meetingPayload(): array
    {
        return [
            '_form' => 'meeting',
            'type' => 'meeting',
            'name' => 'Dr. Sample',
            'email' => 'dr.sample@example.com',
            'phone' => '+1 555 010 0100',
            'practice_name' => 'Sample Family Clinic',
            'specialty' => 'Cardiology',
            'preferred_date' => '2026-10-20',
        ];
    }
}
