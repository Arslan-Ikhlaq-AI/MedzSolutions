<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function index(): View
    {
        return view('site', [
            'page' => 'services.index',
            'title' => 'Services',
            'description' => 'Revenue cycle management, medical billing, coding, eligibility, prior authorization, denial management, AR follow-up and credentialing services.',
            'props' => [
                'content' => config('site.services_page'),
                'services' => $this->content->services(),
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $service = $this->content->service($slug);

        return view('site', [
            'page' => 'services.show',
            'title' => $service['title'],
            'description' => $service['excerpt'],
            'props' => [
                'service' => $service,
                'content' => config('site.service_page'),
                'values' => config('site.home.benefits'),
                'services' => $this->content->services(),
            ],
        ]);
    }
}
