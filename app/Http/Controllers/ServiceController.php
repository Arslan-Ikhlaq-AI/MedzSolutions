<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function index(): View
    {
        return view('site', [
            'page' => 'services.index',
            'title' => 'Services',
            'description' => 'Provider credentialing and enrollment, medical billing and revenue cycle management, and medical coding services.',
            'props' => [
                'content' => config('site.services_page'),
                'headerImage' => asset(config('site.services_page.header_image')),
                'services' => $this->content->services(),
                'onboarding' => config('site.home.onboarding'),
                'commitments' => config('site.home.commitments'),
                'faqs' => collect(config('site.faqs'))->take(5)->values(),
            ],
        ]);
    }

    /**
     * A core service page. Addresses of the older, merged service pages redirect permanently to their replacement.
     */
    public function show(string $slug): View|RedirectResponse
    {
        $replacementSlug = config('site.service_redirects', [])[$slug] ?? null;

        if ($replacementSlug) {
            return redirect()->route('services.show', $replacementSlug, 301);
        }

        $service = $this->content->service($slug);

        return view('site', [
            'page' => 'services.show',
            'title' => $service['title'],
            'description' => $service['excerpt'],
            'props' => [
                'service' => $service,
                'content' => config('site.service_page'),
                'headerImage' => asset($service['header_image'] ?? config('site.services_page.header_image')),
                'values' => config('site.home.benefits'),
                'onboarding' => config('site.home.onboarding'),
                'commitments' => config('site.home.commitments'),
                'services' => $this->content->services(),
            ],
        ]);
    }
}
