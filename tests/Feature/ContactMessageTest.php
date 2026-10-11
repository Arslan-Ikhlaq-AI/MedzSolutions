<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_valid_message_is_stored_and_the_visitor_sees_a_confirmation(): void
    {
        $response = $this->from('/compliance')->post('/contact-us', [
            '_form' => 'contact',
            'name' => 'Sample Visitor',
            'email' => 'visitor@example.com',
            'phone' => '+1 555 010 0199',
            'subject' => 'New patient portal',
            'message' => 'We would like to discuss a patient portal.',
        ]);

        $response->assertRedirect('/compliance');
        $response->assertSessionHas('status', 'Thank you! Your message has been sent. We will be in touch within one working day.');
        $response->assertSessionHas('form', 'contact');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Sample Visitor',
            'email' => 'visitor@example.com',
            'phone' => '+1 555 010 0199',
            'subject' => 'New patient portal',
            'message' => 'We would like to discuss a patient portal.',
        ]);
    }

    public function test_empty_message_is_rejected_with_required_field_errors(): void
    {
        $response = $this->from('/contact-us')->post('/contact-us', []);

        $response->assertRedirect('/contact-us');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'message' => 'The message field is required.',
        ]);
        $response->assertSessionDoesntHaveErrors(['phone', 'subject']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_invalid_email_address_is_rejected(): void
    {
        $response = $this->from('/contact-us')->post('/contact-us', [
            'name' => 'Sample Visitor',
            'email' => 'not-an-email',
            'message' => 'Hello there.',
        ]);

        $response->assertSessionHasErrors(['email' => 'The email field must be a valid email address.']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_message_longer_than_5000_characters_is_rejected(): void
    {
        $response = $this->from('/contact-us')->post('/contact-us', [
            'name' => 'Sample Visitor',
            'email' => 'visitor@example.com',
            'message' => str_repeat('a', 5001),
        ]);

        $response->assertSessionHasErrors(['message' => 'The message field must not be greater than 5000 characters.']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_previous_input_is_escaped_when_the_form_is_shown_again(): void
    {
        $response = $this->followingRedirects()->from('/contact-us')->post('/contact-us', [
            'name' => '</script><script>alert("owned")</script>',
            'email' => 'not-an-email',
            'message' => 'Hello there.',
        ]);

        $response->assertOk();
        $response->assertDontSee('</script><script>alert("owned")</script>', false);
    }

    public function test_sixth_message_within_a_minute_returns_429(): void
    {
        $payload = ContactMessage::factory()->make()->only(['name', 'email', 'phone', 'subject', 'message']);

        foreach (range(1, 5) as $attempt) {
            $this->from('/contact-us')->post('/contact-us', $payload)->assertRedirect('/contact-us');
        }

        $response = $this->from('/contact-us')->post('/contact-us', $payload);

        $response->assertTooManyRequests();

        $this->assertDatabaseCount('contact_messages', 5);
    }
}
