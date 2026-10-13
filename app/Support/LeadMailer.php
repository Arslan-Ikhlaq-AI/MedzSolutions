<?php

namespace App\Support;

use App\Mail\NewSubmissionAlert;
use App\Mail\SubmissionFollowUp;
use App\Mail\SubmissionReceived;
use App\Models\ConsultationRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

use function Illuminate\Support\defer;

/**
 * Emails about enquiries submitted through the website: a confirmation to the person who sent
 * it, an alert to the team and, later, a follow-up if nobody has picked it up yet. Emails only
 * ever go to people who gave us their address in a form.
 */
class LeadMailer
{
    /**
     * @var array<string, string>
     */
    public const LABELS = [
        'contact' => 'Contact message',
        'meeting' => 'Meeting request',
        'review' => 'Free practice review request',
        'application' => 'Job application',
        'affiliate' => 'Affiliate application',
    ];

    public function __construct(private AdminInbox $inbox, private VisitorJourney $journeys) {}

    /**
     * Sends the confirmation and the team alert once the visitor has already seen the
     * "thank you" page, so a slow mail server never slows the form down.
     */
    public function submitted(Model $record, string $type): void
    {
        defer(function () use ($record, $type): void {
            $details = $this->details($record, $type);

            $this->send($record->email, new SubmissionReceived($record->name, $type, $details));

            if ($notify = config('site.emails.notify')) {
                $section = $this->inbox->sectionFor($record);

                $this->send($notify, new NewSubmissionAlert(
                    name: $record->name,
                    email: $record->email,
                    phone: $record->phone,
                    label: self::LABELS[$type],
                    details: $details,
                    message: $record->message ?? $record->cover_letter ?? $record->audience ?? null,
                    journey: $this->journeys->before($record->visitor_id, $record->created_at),
                    adminUrl: route($section['route'].'.show', $record->getKey()),
                ));
            }
        });
    }

    /**
     * Sends the follow-up email straight away and reports whether it went out.
     */
    public function followUp(Model $record, string $type): bool
    {
        return $this->send($record->email, new SubmissionFollowUp($record->name, self::LABELS[$type]));
    }

    /**
     * The type of enquiry a record is, as used for the email wording.
     */
    public static function typeOf(Model $record): string
    {
        return $record instanceof ConsultationRequest ? $record->type : match (class_basename($record)) {
            'ContactMessage' => 'contact',
            'JobApplication' => 'application',
            'AffiliateApplication' => 'affiliate',
        };
    }

    /**
     * A short summary of what was submitted, shown in both emails.
     *
     * @return array<string, string>
     */
    private function details(Model $record, string $type): array
    {
        $details = match ($type) {
            'contact' => ['Subject' => $record->subject],
            'meeting' => [
                'Practice' => $record->practice_name,
                'Preferred date' => $record->preferred_date?->format('l, F j, Y'),
            ],
            'review' => [
                'Practice' => $record->practice_name,
                'Specialty' => $record->specialty,
                'Challenges' => collect($record->challenges ?? [])->map(fn (string $key): string => config("site.revenue_challenges.{$key}", $key))->implode('; '),
            ],
            'application' => ['Position' => $record->job_title],
            'affiliate' => [
                'Partner type' => $record->partner_type,
                'Company' => $record->company,
            ],
        };

        return array_map(fn (string $value): string => Str::limit($value, 300), array_filter($details, 'filled'));
    }

    private function send(string $to, Mailable $mail): bool
    {
        try {
            Mail::to($to)->send($mail);

            return true;
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }
}
