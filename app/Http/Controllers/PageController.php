<?php

namespace App\Http\Controllers;

use App\Support\CookieCatalog;
use App\Support\SiteContent;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function home(): View
    {
        $specialities = collect(config('site.specialities'));

        return view('site', [
            'page' => 'home',
            'title' => 'Medical Billing & Revenue Cycle Management Services',
            'description' => config('site.company.summary'),
            'props' => [
                'content' => [
                    ...config('site.home'),
                    'slides' => collect(config('site.home.slides'))
                        ->map(fn (array $slide): array => [...$slide, 'image' => asset($slide['image'])])
                        ->all(),
                ],
                'photos' => [
                    'welcome' => asset(config('site.home.welcome.image')),
                    'choose' => asset(config('site.home.choose.image')),
                    'support' => asset(config('site.home.support.image')),
                ],
                'services' => $this->content->services()->take(6)->values(),
                'specialities' => $specialities->take(8)->values(),
                'specialityNames' => $specialities->pluck('name'),
                'software' => config('site.software.ehr'),
                'challenges' => config('site.revenue_challenges'),
                'faqs' => collect(config('site.faqs'))->take(5)->values(),
                'testimonials' => config('site.testimonials'),
            ],
        ]);
    }

    public function about(): View
    {
        return view('site', [
            'page' => 'about',
            'title' => 'About Us',
            'description' => 'Learn how the Medz-Solutions team of billers, coders and account managers helps healthcare practices get paid accurately and on time.',
            'props' => [
                'content' => config('site.about'),
                'commitments' => config('site.home.commitments'),
            ],
        ]);
    }

    public function team(): View
    {
        return view('site', [
            'page' => 'team',
            'title' => 'Meet Our Team',
            'description' => 'Meet the Medz-Solutions teams behind every clean claim: account management, billing, coding, AR, credentialing and patient support.',
            'props' => [
                'content' => config('site.team_page'),
            ],
        ]);
    }

    public function specialities(): View
    {
        return view('site', [
            'page' => 'specialities',
            'title' => 'Specialities',
            'description' => 'Medical billing and coding support for more than twenty medical specialties, from cardiology to telehealth.',
            'props' => [
                'content' => config('site.specialities_page'),
                'specialities' => config('site.specialities'),
            ],
        ]);
    }

    public function software(): View
    {
        return view('site', [
            'page' => 'software',
            'title' => 'Software',
            'description' => 'The EHR, practice management and clearinghouse platforms the Medz-Solutions billing team works with.',
            'props' => [
                'content' => config('site.software_page'),
                'software' => config('site.software'),
            ],
        ]);
    }

    public function compliance(): View
    {
        return view('site', [
            'page' => 'compliance',
            'title' => 'Compliance',
            'description' => 'How Medz-Solutions protects patient information and keeps billing work aligned with HIPAA and coding guidelines.',
            'props' => [
                'content' => config('site.compliance_page'),
            ],
        ]);
    }

    public function cookiePolicy(CookieCatalog $cookies): View
    {
        return view('site', [
            'page' => 'cookie-policy',
            'title' => 'Cookie Policy',
            'description' => 'Which cookies Medz-Solutions uses, why we use them and how you can change your choices at any time.',
            'props' => [
                'content' => config('site.cookie_policy_page'),
                'categories' => $cookies->categories(),
                'version' => (string) config('site.cookies.version'),
            ],
        ]);
    }

    public function faqs(): View
    {
        return view('site', [
            'page' => 'faqs',
            'title' => 'FAQs',
            'description' => 'Answers to common questions about outsourcing medical billing to Medz-Solutions, from pricing and onboarding to data security.',
            'props' => [
                'content' => config('site.faqs_page'),
                'faqs' => config('site.faqs'),
            ],
        ]);
    }
}
