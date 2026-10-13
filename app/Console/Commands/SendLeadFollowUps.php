<?php

namespace App\Console\Commands;

use App\Enums\RequestStatus;
use App\Models\ConsultationRequest;
use App\Models\ContactMessage;
use App\Support\LeadMailer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

#[Signature('leads:send-follow-ups')]
#[Description('Email a friendly follow-up to contact and demo/review enquiries nobody has picked up yet')]
class SendLeadFollowUps extends Command
{
    /**
     * Enquiries older than this are left alone, so a first run never emails old contacts.
     */
    private const MAX_AGE_DAYS = 14;

    public function handle(LeadMailer $mailer): int
    {
        $days = (int) config('site.emails.follow_up_after_days');
        $sent = 0;

        foreach ([ContactMessage::class, ConsultationRequest::class] as $model) {
            $model::query()
                ->where('status', RequestStatus::New)
                ->whereNull('follow_up_sent_at')
                ->where('created_at', '<=', now()->subDays($days))
                ->where('created_at', '>=', now()->subDays(self::MAX_AGE_DAYS))
                ->each(function (Model $record) use ($mailer, &$sent): void {
                    if ($mailer->followUp($record, LeadMailer::typeOf($record))) {
                        $record->forceFill(['follow_up_sent_at' => now()])->save();
                        $sent++;
                    }
                });
        }

        $this->info("Sent {$sent} follow-up ".str('email')->plural($sent).'.');

        return self::SUCCESS;
    }
}
