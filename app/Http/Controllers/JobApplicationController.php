<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Support\LeadMailer;
use App\Support\SiteContent;
use App\Support\VisitorConsent;
use Illuminate\Http\RedirectResponse;

class JobApplicationController extends Controller
{
    public function store(StoreJobApplicationRequest $request, SiteContent $content, LeadMailer $mailer, string $slug): RedirectResponse
    {
        $job = $content->job($slug);

        $application = JobApplication::create([
            ...$request->safe()->except('resume'),
            'visitor_id' => VisitorConsent::fromRequest($request)->trackableId(),
            'job_slug' => $slug,
            'job_title' => $job['title'],
            'resume_path' => $request->file('resume')->store('resumes'),
        ]);

        $mailer->submitted($application, 'application');

        return back()
            ->with('status', config('site.form_messages.application'))
            ->with('form', 'application');
    }
}
