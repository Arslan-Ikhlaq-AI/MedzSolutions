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
        <div class="detail-main">
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

        <section class="card">
            <div class="card-head">
                <h3>Visitor journey</h3>
                @if ($journey)
                    <span class="muted">{{ $journey['page_count'] }} {{ \Illuminate\Support\Str::plural('page', $journey['page_count']) }} over {{ $journey['visit_days'] }} {{ \Illuminate\Support\Str::plural('day', $journey['visit_days']) }}</span>
                @endif
            </div>
            @if ($journey)
                <dl class="journey-facts">
                    <div><dt>First visit</dt><dd>{{ $journey['first_seen']->format('M j, Y g:i A') }}</dd></div>
                    <div><dt>Came from</dt><dd>{{ $journey['source'] }}</dd></div>
                    <div><dt>Before contacting us</dt><dd>{{ $journey['first_seen']->diffForHumans($record->created_at, ['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</dd></div>
                </dl>
                <ol class="timeline">
                    @foreach ($journey['pages'] as $page)
                        <li>
                            <time datetime="{{ $page->created_at->toIso8601String() }}">{{ $page->created_at->format('M j, g:i A') }}</time>
                            <span class="timeline-page">
                                <strong>{{ $page->title ?: $page->path }}</strong>
                                <a href="{{ url($page->path) }}" target="_blank" rel="noopener">{{ $page->path }}</a>
                            </span>
                        </li>
                    @endforeach
                    <li class="timeline-end">
                        <time datetime="{{ $record->created_at->toIso8601String() }}">{{ $record->created_at->format('M j, g:i A') }}</time>
                        <span class="timeline-page"><strong>Sent this {{ strtolower($section['singular']) }}</strong></span>
                    </li>
                </ol>
            @else
                <p class="muted">No visit history for this enquiry. The visitor did not allow analytics cookies, so we only know what they wrote in the form.</p>
            @endif
        </section>
        </div>

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
                @if ($record->follow_up_sent_at)
                    <p class="muted small-print form-note">Follow-up email sent {{ $record->follow_up_sent_at->format('M j, Y') }}.</p>
                @elseif (in_array($sectionKey, ['contact-messages', 'consultations'], true) && $record->status->value === 'new')
                    <p class="muted small-print form-note">If this stays "New", a follow-up email goes out {{ config('site.emails.follow_up_after_days') }} days after it arrived.</p>
                @endif
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
