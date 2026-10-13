@extends('admin.layouts.app')

@section('title', 'Blog posts')
@section('heading', 'Blog posts')

@section('content')
    <div class="page-head">
        <div>
            <h2>Blog posts</h2>
            <p class="muted">Articles shown on the public blog. Drafts and scheduled posts stay hidden until their publish date.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.posts.create') }}"><x-admin.icon name="plus" /> New post</a>
    </div>

    <nav class="tabs" aria-label="Post status">
        <a @class(['tab', 'is-active' => ! $filters['status']]) href="{{ route('admin.posts.index') }}">All <span>{{ $counts['all'] }}</span></a>
        <a @class(['tab', 'is-active' => $filters['status'] === 'published']) href="{{ route('admin.posts.index', ['status' => 'published']) }}">Published <span>{{ $counts['published'] }}</span></a>
        <a @class(['tab', 'is-active' => $filters['status'] === 'draft']) href="{{ route('admin.posts.index', ['status' => 'draft']) }}">Drafts <span>{{ $counts['draft'] }}</span></a>
    </nav>

    <section class="card card-flush">
        <form class="toolbar" method="get" action="{{ route('admin.posts.index') }}">
            @if ($filters['status'])
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            @endif
            <label class="search">
                <x-admin.icon name="search" />
                <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search title or category…" aria-label="Search">
            </label>
            <button type="submit" class="btn btn-ghost">Search</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Post</th><th>Category</th><th>Status</th><th>Author</th><th>Publish date</th><th class="col-actions">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>
                                <div class="post-cell">
                                    @if ($post->featuredImageUrl())
                                        <img class="thumb" src="{{ $post->featuredImageUrl() }}" alt="" width="64" height="44" loading="lazy">
                                    @else
                                        <span class="thumb thumb-empty"><x-admin.icon name="image" /></span>
                                    @endif
                                    <span>
                                        <a class="row-title" href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a>
                                        <span class="row-sub">/blogs/{{ $post->slug }}</span>
                                    </span>
                                </div>
                            </td>
                            <td>{{ $post->category }}</td>
                            <td>
                                @if ($post->isPublished())
                                    <span class="badge badge-green">Published</span>
                                @elseif ($post->status === 'published')
                                    <span class="badge badge-gold">Scheduled</span>
                                @else
                                    <span class="badge badge-muted">Draft</span>
                                @endif
                            </td>
                            <td>{{ $post->author?->name ?? '—' }}</td>
                            <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    @if ($post->isPublished())
                                        <a class="icon-button" href="{{ route('posts.show', $post->slug) }}" target="_blank" rel="noopener" aria-label="View {{ $post->title }} on the website"><x-admin.icon name="eye" /></a>
                                    @endif
                                    <a class="icon-button" href="{{ route('admin.posts.edit', $post) }}" aria-label="Edit {{ $post->title }}"><x-admin.icon name="edit" /></a>
                                    <form method="post" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Delete “{{ $post->title }}” permanently?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-button icon-danger" aria-label="Delete {{ $post->title }}"><x-admin.icon name="trash" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-cell"><div class="empty-state"><x-admin.icon name="file" /><p>No posts match. <a href="{{ route('admin.posts.create') }}">Write the first one</a>.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $posts->links('admin.partials.pagination') }}
    </section>
@endsection
