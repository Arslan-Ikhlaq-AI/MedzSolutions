<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;

/**
 * A friendly follow-up to someone whose enquiry has not been picked up yet.
 */
class SubmissionFollowUp extends Mailable
{
    use Queueable;

    public function __construct(public string $name, public string $label) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: array_filter([config('site.emails.reply_to') ? new Address(config('site.emails.reply_to'), config('site.company.name')) : null]),
            subject: config('site.emails.follow_up.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.submission-follow-up',
            with: [
                'firstName' => Str::before(trim($this->name), ' '),
                'body' => config('site.emails.follow_up.body'),
                'company' => config('site.company'),
                'bookingUrl' => route('contact'),
            ],
        );
    }
}
