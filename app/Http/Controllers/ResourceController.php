<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function index(): View
    {
        return view('site', [
            'page' => 'resources.index',
            'title' => 'Resources',
            'description' => 'Free checklists and guides that help healthcare practices improve eligibility checks, credentialing and denial prevention.',
            'props' => [
                'content' => config('site.resources_page'),
                'resources' => $this->content->resources(),
                'posts' => $this->content->posts()->take(3)->values(),
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $resource = $this->content->resource($slug);

        return view('site', [
            'page' => 'resources.show',
            'title' => $resource['title'],
            'description' => $resource['excerpt'],
            'props' => [
                'resource' => $resource,
                'related' => $this->content->resources()->where('slug', '!=', $slug)->take(3)->values(),
            ],
        ]);
    }
}
