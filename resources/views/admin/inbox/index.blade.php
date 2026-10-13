@extends('admin.layouts.app')

@section('title', $section['title'])
@section('heading', $section['title'])

@section('content')
    @php($route = $section['route'])
    <div class="page-head">
        <div>
            <h2>{{ $section['title'] }}</h2>
            <p class="muted">{{ $records->total() }} {{ \Illuminate\Support\Str::plural('record', $records->total()) }}@if ($filters['q'] || $filters['status'] || $filters['filter']) matching your filters @endif</p>
        </div>
    </div>

    @if ($tabs)
        <nav class="tabs" aria-label="Request type">
            @foreach ($tabs as $tab)
                <a @class(['tab', 'is-active' => ($filters['type'] ?? '') === $tab['value']]) href="{{ route($route.'.index', array_filter(['type' => $tab['value']])) }}">{{ $tab['label'] }}</a>
            @endforeach
        </nav>
    @endif

    <section class="card card-flush">
        <form class="toolbar" method="get" action="{{ route($route.'.index') }}">
            @if ($filters['type'])
                <input type="hidden" name="type" value="{{ $filters['type'] }}">
            @endif
            <label class="search">
                <x-admin.icon name="search" />
                <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search name, email, message…" aria-label="Search">
            </label>
            <select name="status" aria-label="Status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="filter" aria-label="Read state" onchange="this.form.submit()">
                <option value="">Read and unread</option>
                <option value="unread" @selected($filters['filter'] === 'unread')>Unread only</option>
            </select>
            <button type="submit" class="btn btn-ghost">Filter</button>
            @if ($filters['q'] || $filters['status'] || $filters['filter'])
                <a class="link-button" href="{{ route($route.'.index', array_filter(['type' => $filters['type']])) }}">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>From</th>
                        @foreach ($columnLabels as $label)
                            <th>{{ $label }}</th>
                        @endforeach
                        <th>Status</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr @class(['is-unread' => $row['unread']])>
                            <td>
                                <a class="row-title" href="{{ route($route.'.show', $row['id']) }}">
                                    @if ($row['unread'])<span class="unread-dot" title="Unread"></span>@endif
                                    {{ $row['title'] }}
                                </a>
                                <span class="row-sub">{{ $row['subtitle'] }}</span>
                            </td>
                            @foreach ($row['cells'] as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                            <td>@include('admin.partials.status-badge', ['status' => $row['status']])</td>
                            <td><time datetime="{{ $row['received']->toIso8601String() }}" title="{{ $row['received']->format('M j, Y g:i A') }}">{{ $row['received']->diffForHumans() }}</time></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columnLabels) + 3 }}" class="empty-cell">
                                <div class="empty-state">
                                    <x-admin.icon name="inbox" />
                                    <p>Nothing here yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $records->links('admin.partials.pagination') }}
    </section>
@endsection
