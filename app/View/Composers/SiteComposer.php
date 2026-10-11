<?php

namespace App\View\Composers;

use App\Support\SiteContent;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * Shares the header, footer and contact details every marketing page needs.
 */
class SiteComposer
{
    public function __construct(private SiteContent $content) {}

    public function compose(View $view): void
    {
        $view->with('shared', [
            'company' => [...config('site.company'), 'map' => $this->mapLinks()],
            'brand' => [
                'mark' => asset('images/brand/logo-mark-128.png'),
            ],
            'services' => $this->content->services()
                ->map(fn (array $service): array => Arr::only($service, ['title', 'url']))
                ->all(),
            'routes' => [
                'home' => route('home'),
                'about' => route('about'),
                'team' => route('team'),
                'services' => route('services.index'),
                'specialities' => route('specialities'),
                'software' => route('software'),
                'compliance' => route('compliance'),
                'resources' => route('resources.index'),
                'blog' => route('posts.index'),
                'careers' => route('careers.index'),
                'affiliate' => route('affiliate'),
                'faqs' => route('faqs'),
                'contact' => route('contact'),
                'contactStore' => route('contact.store'),
                'consultations' => route('consultations.store'),
            ],
            'currentUrl' => url()->current(),
            'year' => now()->year,
        ]);
    }

    /**
     * Google Maps links for the configured location. The official Maps Embed API is used
     * when a key is configured; otherwise the free keyless embed is used.
     *
     * @return array{embed: string, directions: string}
     */
    private function mapLinks(): array
    {
        $map = config('site.company.map');
        $key = config('services.google_maps.embed_key');

        $embed = $key
            ? 'https://www.google.com/maps/embed/v1/place?'.http_build_query(['key' => $key, 'q' => $map['query'], 'zoom' => $map['zoom']])
            : 'https://maps.google.com/maps?'.http_build_query(['q' => $map['query'], 'z' => $map['zoom'], 'output' => 'embed']);

        return [
            'embed' => $embed,
            'directions' => 'https://www.google.com/maps/search/?'.http_build_query(['api' => 1, 'query' => $map['query']]),
        ];
    }
}
