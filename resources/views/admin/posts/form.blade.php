@extends('admin.layouts.app')

@php($isEditing = $post->exists)

@section('title', $isEditing ? 'Edit post' : 'New post')
@section('heading', $isEditing ? 'Edit post' : 'New post')

@section('content')
    <form class="editor" method="post" enctype="multipart/form-data" action="{{ $isEditing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" data-post-form>
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <div class="page-head">
            <div>
                <a class="back-link" href="{{ route('admin.posts.index') }}">← All posts</a>
                <h2>{{ $isEditing ? 'Edit post' : 'Write a new post' }}</h2>
            </div>
            @if ($isEditing && $post->isPublished())
                <a class="btn btn-ghost" href="{{ route('posts.show', $post->slug) }}" target="_blank" rel="noopener"><x-admin.icon name="external" /> View on website</a>
            @endif
        </div>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">Please fix the highlighted fields and save again.</div>
        @endif

        <div class="editor-grid">
            <div class="editor-main">
                <section class="card">
                    <label class="field">
                        <span class="field-label">Title</span>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" maxlength="160" required data-slug-source>
                        @error('title')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    <label class="field">
                        <span class="field-label">Web address</span>
                        <span class="slug-input"><span>/blogs/</span><input type="text" name="slug" value="{{ old('slug', $post->slug) }}" maxlength="160" data-slug-target placeholder="generated-from-the-title"></span>
                        @error('slug')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    <label class="field">
                        <span class="field-label">Summary <small data-counter-for="excerpt">0/300</small></span>
                        <textarea name="excerpt" rows="3" maxlength="300" required data-counter="excerpt" placeholder="One or two sentences shown on blog cards and in search results">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                </section>

                <section class="card">
                    <label class="field">
                        <span class="field-label">Article</span>
                        <textarea class="editor-body" name="body" rows="22" required placeholder="Write the article here. Markdown is supported.">{{ old('body', $post->body) }}</textarea>
                        @error('body')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    <details class="help">
                        <summary>Formatting help (Markdown)</summary>
                        <table class="help-table">
                            <tr><td><code>## Heading</code></td><td>Section heading (use <code>###</code> for a sub-heading)</td></tr>
                            <tr><td><code>**bold**</code> · <code>*italic*</code></td><td>Emphasis</td></tr>
                            <tr><td><code>- item</code></td><td>Bulleted list (use <code>1.</code> for a numbered list)</td></tr>
                            <tr><td><code>[text](https://…)</code></td><td>Link</td></tr>
                        </table>
                        <p class="muted">Raw HTML is removed for safety.</p>
                    </details>
                </section>
            </div>

            <aside class="editor-side">
                <section class="card">
                    <h3>Publish</h3>
                    <label class="field">
                        <span class="field-label">Status</span>
                        <select name="status">
                            <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft (hidden)</option>
                            <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                        </select>
                        @error('status')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    <label class="field">
                        <span class="field-label">Publish date</span>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                        <small class="muted">Leave empty to publish now. A future date schedules the post.</small>
                        @error('published_at')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    <button type="submit" class="btn btn-primary btn-block">{{ $isEditing ? 'Save changes' : 'Save post' }}</button>
                </section>

                <section class="card">
                    <h3>Category</h3>
                    <label class="field">
                        <input type="text" name="category" value="{{ old('category', $post->category) }}" list="post-categories" maxlength="60" required placeholder="e.g. Denials">
                        <datalist id="post-categories">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}"></option>
                            @endforeach
                        </datalist>
                        @error('category')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                </section>

                <section class="card">
                    <h3>Featured image</h3>
                    <div class="image-preview" data-image-preview>
                        @if ($post->featuredImageUrl())
                            <img src="{{ $post->featuredImageUrl() }}" alt="Current featured image">
                        @else
                            <span class="muted">No image yet</span>
                        @endif
                    </div>
                    <label class="field">
                        <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp" data-image-input>
                        <small class="muted">JPG, PNG or WebP, up to 4 MB. Landscape photos work best.</small>
                        @error('featured_image')<span class="field-error">{{ $message }}</span>@enderror
                    </label>
                    @if ($post->featured_image)
                        <label class="check"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                    @endif
                    <label class="field">
                        <span class="field-label">Illustration when there is no image</span>
                        <select name="artwork">
                            @foreach ($artworkOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('artwork', $post->artwork) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </section>
            </aside>
        </div>
    </form>
@endsection
