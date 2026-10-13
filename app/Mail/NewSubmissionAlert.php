<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Tells the team about a new enquiry, including what the visitor read before sending it.
 * Replying to this email goes straight to the visitor.
 */
class NewSubmissionAlert extends Mailable
{
    use Queueable;

    /**
     * @param  array<string, string>  $details
     * @param  array<string, mixed>|null  $journey
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $label,
        public array $details,
        public ?string $message,
        public ?array $journey,
        public string $adminUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->email, $this->name)],
            subject: "New {$this->label}: {$this->name}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-submission-alert');
    }
}
