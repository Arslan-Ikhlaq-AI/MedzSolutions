<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;

class JobApplicationController extends Controller
{
    public function store(StoreJobApplicationRequest $request, SiteContent $content, string $slug): RedirectResponse
    {
        $job = $content->job($slug);

        JobApplication::create([
            ...$request->safe()->except('resume'),
            'job_slug' => $slug,
            'job_title' => $job['title'],
            'resume_path' => $request->file('resume')->store('resumes'),
        ]);

        return back()
            ->with('status', config('site.form_messages.application'))
            ->with('form', 'application');
    }
}
