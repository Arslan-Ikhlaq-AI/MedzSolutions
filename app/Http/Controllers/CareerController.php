<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        return view('site', [
            'page' => 'careers.index',
            'title' => 'Careers',
            'description' => 'Join the Medz-Solutions team. Explore open roles in medical billing, coding, credentialing and account management.',
            'props' => [
                'content' => config('site.careers_page'),
                'headerImage' => asset(config('site.careers_page.header_image')),
                'teamImage' => asset(config('site.careers_page.team_image')),
                'jobs' => $this->content->jobs($search),
                'search' => $search,
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $job = $this->content->job($slug);

        return view('site', [
            'page' => 'careers.show',
            'title' => $job['title'],
            'description' => $job['excerpt'],
            'props' => [
                'job' => $job,
                'content' => config('site.careers_page'),
                'action' => route('careers.apply', $slug),
                'otherJobs' => $this->content->jobs()->where('slug', '!=', $slug)->take(3)->values(),
            ],
        ]);
    }
}
