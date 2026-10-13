<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;

/**
 * Confirms to the person who filled in a form that we received it.
 */
class SubmissionReceived extends Mailable
{
    use Queueable;

    /**
     * @param  array<string, string>  $details
     */
    public function __construct(public string $name, public string $type, public array $details) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: array_filter([config('site.emails.reply_to') ? new Address(config('site.emails.reply_to'), config('site.company.name')) : null]),
            subject: config("site.emails.confirmations.{$this->type}.subject"),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.submission-received',
            with: [
                'firstName' => Str::before(trim($this->name), ' '),
                'body' => config("site.emails.confirmations.{$this->type}.body"),
                'company' => config('site.company'),
                'websiteUrl' => route('home'),
            ],
        );
    }
}
