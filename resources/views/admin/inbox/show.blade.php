@extends('admin.layouts.app')

@section('title', $title)
@section('heading', $section['singular'])

@section('content')
    @php($route = $section['route'])
    <div class="page-head">
        <div>
            <a class="back-link" href="{{ route($route.'.index') }}">← All {{ strtolower($section['title']) }}</a>
            <h2>{{ $title }}</h2>
            <p class="muted">Received {{ $record->created_at->format('l, M j, Y \a\t g:i A') }} · {{ $record->created_at->diffForHumans() }}</p>
        </div>
        @include('admin.partials.status-badge', ['status' => $record->status])
    </div>

    <div class="detail-grid">
        <section class="card">
            <dl class="details">
                @foreach ($details as $detail)
                    @continue(blank($detail['value']))
                    <div class="detail">
                        <dt>{{ $detail['label'] }}</dt>
                        <dd>
                            @switch($detail['kind'] ?? 'text')
                                @case('email')
                                    <a href="mailto:{{ $detail['value'] }}">{{ $detail['value'] }}</a>
                                    @break
                                @case('phone')
                                    <a href="tel:{{ preg_replace('/[^+\d]/', '', $detail['value']) }}">{{ $detail['value'] }}</a>
                                    @break
                                @case('link')
                                    <a href="{{ $detail['value'] }}" target="_blank" rel="noopener noreferrer">{{ $detail['value'] }}</a>
                                    @break
                                @case('long')
                                    <p class="long-text">{{ $detail['value'] }}</p>
                                    @break
                                @case('tags')
                                    <ul class="tags">
                                        @foreach ($detail['value'] as $tag)
                                            <li>{{ $tag }}</li>
                                        @endforeach
                                    </ul>
                                    @break
                                @default
                                    {{ $detail['value'] }}
                            @endswitch
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <aside class="detail-side">
            <form class="card" method="post" action="{{ route($route.'.update', $record->getKey()) }}">
                @csrf
                @method('PUT')
                <h3>Follow-up</h3>
                <label class="field">
                    <span class="field-label">Status</span>
                    <select name="status">
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $record->status->value) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <label class="field">
                    <span class="field-label">Internal notes</span>
                    <textarea name="admin_notes" rows="6" placeholder="Only visible to admins">{{ old('admin_notes', $record->admin_notes) }}</textarea>
                    @error('admin_notes')<span class="field-error">{{ $message }}</span>@enderror
                </label>
                <button type="submit" class="btn btn-primary btn-block">Save changes</button>
            </form>

            @if ($extraActions)
                <div class="card">
                    <h3>Files</h3>
                    @foreach ($extraActions as $label => $url)
                        <a class="btn btn-ghost btn-block" href="{{ $url }}"><x-admin.icon name="download" /> {{ $label }}</a>
                    @endforeach
                </div>
            @endif

            <form class="card danger-zone" method="post" action="{{ route($route.'.destroy', $record->getKey()) }}" data-confirm="Delete this {{ strtolower($section['singular']) }} permanently? This cannot be undone.">
                @csrf
                @method('DELETE')
                <h3>Delete</h3>
                <p class="muted">Removes this record{{ $extraActions ? ' and its files' : '' }} for good.</p>
                <button type="submit" class="btn btn-danger btn-block"><x-admin.icon name="trash" /> Delete {{ strtolower($section['singular']) }}</button>
            </form>
        </aside>
    </div>
@endsection
