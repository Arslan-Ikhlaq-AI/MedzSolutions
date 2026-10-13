<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->value();
        $status = $request->query('status');

        $posts = Post::query()
            ->with('author')
            ->when(in_array($status, [Post::STATUS_DRAFT, Post::STATUS_PUBLISHED], true), fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search): void {
                $term = '%'.addcslashes($search, '%_\\').'%';

                $query->where(fn ($query) => $query->where('title', 'like', $term)->orWhere('category', 'like', $term));
            })
            ->latest('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'filters' => ['q' => $search, 'status' => $status],
            'counts' => [
                'all' => Post::count(),
                Post::STATUS_PUBLISHED => Post::where('status', Post::STATUS_PUBLISHED)->count(),
                Post::STATUS_DRAFT => Post::where('status', Post::STATUS_DRAFT)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', $this->formData(new Post([
            'status' => Post::STATUS_DRAFT,
            'artwork' => 'pattern',
        ])));
    }

    public function store(SavePostRequest $request): RedirectResponse
    {
        $post = new Post($this->attributes($request));
        $post->user_id = $request->user()->id;
        $post->save();

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', $post->status === Post::STATUS_PUBLISHED ? 'Post published.' : 'Draft saved.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', $this->formData($post));
    }

    public function update(SavePostRequest $request, Post $post): RedirectResponse
    {
        $attributes = $this->attributes($request, $post);

        if (($request->boolean('remove_image') || $request->hasFile('featured_image')) && $post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->update($attributes);

        return back()->with('status', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(SavePostRequest $request, ?Post $post = null): array
    {
        $attributes = $request->safe()->except(['featured_image', 'remove_image', 'published_at']);

        $publishedAt = $request->date('published_at');

        // A published post always needs a publication date; drafts keep whatever was entered.
        $attributes['published_at'] = $attributes['status'] === Post::STATUS_PUBLISHED
            ? ($publishedAt ?? $post?->published_at ?? now())
            : $publishedAt;

        if ($request->hasFile('featured_image')) {
            $attributes['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        } elseif ($request->boolean('remove_image')) {
            $attributes['featured_image'] = null;
        }

        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Post $post): array
    {
        return [
            'post' => $post,
            'categories' => Post::query()->distinct()->orderBy('category')->pluck('category'),
            'artworkOptions' => SavePostRequest::ARTWORK,
        ];
    }
}
