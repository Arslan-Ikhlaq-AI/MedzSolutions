<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private SiteContent $content) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $category = $request->string('category')->value();

        return view('site', [
            'page' => 'posts.index',
            'title' => 'Blogs',
            'description' => 'Practical medical billing, coding and revenue cycle advice from the Medz-Solutions team.',
            'props' => [
                'content' => config('site.posts_page'),
                'posts' => $this->content->posts($search, $category),
                'categories' => $this->content->postCategories(),
                'latest' => $this->content->posts()->take(3)->values(),
                'filters' => ['search' => $search, 'category' => $category],
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->content->post($slug);

        return view('site', [
            'page' => 'posts.show',
            'title' => $post['title'],
            'description' => $post['excerpt'],
            'props' => [
                'post' => $post,
                'recentPosts' => $this->content->posts()->where('slug', '!=', $slug)->take(3)->values(),
            ],
        ]);
    }
}
