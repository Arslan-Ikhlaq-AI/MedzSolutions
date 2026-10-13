@extends('admin.layouts.app')

@section('title', 'Cookie consents')
@section('heading', 'Cookie consents')

@section('content')
    @php($rate = fn (int $count): string => $stats['visitors'] > 0 ? round($count / $stats['visitors'] * 100).'%' : '—')

    <div class="page-head">
        <div>
            <h2>Cookie consents</h2>
            <p class="muted">Every choice visitors make about cookies. Visitors are identified only by a random consent ID; no IP address or browser details are stored. Current policy version: v{{ $policyVersion }}.</p>
        </div>
        <a class="btn btn-ghost" href="{{ route('admin.cookie-consents.export', array_filter($filters)) }}"><x-admin.icon name="download" /> Export CSV</a>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <span class="stat-icon"><x-admin.icon name="users" /></span>
            <span class="stat-body">
                <span class="stat-label">Visitors who chose</span>
                <span class="stat-value">{{ $stats['visitors'] }}</span>
                <span class="stat-meta">{{ $stats['decisions'] }} decisions in total</span>
            </span>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-icon-green"><x-admin.icon name="check" /></span>
            <span class="stat-body">
                <span class="stat-label">Accepted all</span>
                <span class="stat-value">{{ $stats['accepted'] }}</span>
                <span class="stat-meta"><span class="badge badge-green">{{ $rate($stats['accepted']) }}</span> of visitors</span>
            </span>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-icon-gold"><x-admin.icon name="edit" /></span>
            <span class="stat-body">
                <span class="stat-label">Customised</span>
                <span class="stat-value">{{ $stats['custom'] }}</span>
                <span class="stat-meta"><span class="badge badge-gold">{{ $rate($stats['custom']) }}</span> of visitors</span>
            </span>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-icon-muted"><x-admin.icon name="close" /></span>
            <span class="stat-body">
                <span class="stat-label">Rejected all</span>
                <span class="stat-value">{{ $stats['rejected'] }}</span>
                <span class="stat-meta"><span class="badge badge-muted">{{ $rate($stats['rejected']) }}</span> of visitors</span>
            </span>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="card">
            <div class="card-head">
                <h3>Decisions over the last 30 days</h3>
                <ul class="chart-legend">
                    <li><span class="legend-swatch is-accepted"></span>Accepted all</li>
                    <li><span class="legend-swatch is-custom"></span>Customised</li>
                    <li><span class="legend-swatch is-rejected"></span>Rejected all</li>
                </ul>
            </div>
            <div class="chart chart-dense" role="img" aria-label="Cookie decisions per day for the last 30 days">
                @foreach ($trend as $day)
                    <div class="chart-col" title="{{ $day['date']->format('M j') }}: {{ $day['accepted_all'] }} accepted, {{ $day['custom'] }} customised, {{ $day['rejected_all'] }} rejected">
                        <span class="chart-stack" style="height: {{ $day['total'] ? max(4, round($day['total'] / $trendMax * 100)) : 0 }}%">
                            @foreach (['rejected_all' => 'is-rejected', 'custom' => 'is-custom', 'accepted_all' => 'is-accepted'] as $action => $class)
                                @if ($day[$action] > 0)
                                    <span class="{{ $class }}" style="flex-grow: {{ $day[$action] }}"></span>
                                @endif
                            @endforeach
                        </span>
                        <span class="chart-label">{{ $loop->first || $day['date']->day === 1 || $loop->iteration % 5 === 0 ? $day['date']->format('M j') : '' }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card">
            <div class="card-head"><h3>Opt-in by category</h3></div>
            <ul class="meters">
                @foreach (['analytics' => 'Analytics', 'marketing' => 'Marketing', 'preferences' => 'Preferences'] as $key => $label)
                    <li>
                        <span class="meter-label"><span>{{ $label }}</span><strong>{{ $rate($stats[$key]) }}</strong></span>
                        <span class="meter"><span style="width: {{ $stats['visitors'] ? round($stats[$key] / $stats['visitors'] * 100) : 0 }}%"></span></span>
                    </li>
                @endforeach
            </ul>
            <p class="muted small-print">Based on each visitor's latest choice. {{ $stats['gpc'] }} {{ \Illuminate\Support\Str::plural('visitor', $stats['gpc']) }} sent a Global Privacy Control signal.</p>
        </section>
    </div>

    <nav class="tabs" aria-label="Decision">
        @foreach (['' => 'All', 'accepted_all' => 'Accepted all', 'custom' => 'Customised', 'rejected_all' => 'Rejected all'] as $value => $label)
            <a @class(['tab', 'is-active' => ($filters['action'] ?? '') === $value]) href="{{ route('admin.cookie-consents.index', array_filter(['action' => $value, 'q' => $filters['q']])) }}">{{ $label }}</a>
        @endforeach
    </nav>

    <section class="card card-flush">
        <form class="toolbar" method="get" action="{{ route('admin.cookie-consents.index') }}">
            @if ($filters['action'])
                <input type="hidden" name="action" value="{{ $filters['action'] }}">
            @endif
            <label class="search">
                <x-admin.icon name="search" />
                <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Find a visitor by consent ID…" aria-label="Consent ID">
            </label>
            <button type="submit" class="btn btn-ghost">Search</button>
            @if ($filters['q'])
                <a class="link-button" href="{{ route('admin.cookie-consents.index', array_filter(['action' => $filters['action']])) }}">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Date</th><th>Consent ID</th><th>Decision</th><th>Analytics</th><th>Marketing</th><th>Preferences</th><th>Made on</th><th>Page</th></tr>
                </thead>
                <tbody>
                    @forelse ($decisions as $decision)
                        <tr>
                            <td><time datetime="{{ $decision->created_at->toIso8601String() }}" title="{{ $decision->created_at->format('M j, Y g:i A') }}">{{ $decision->created_at->diffForHumans() }}</time></td>
                            <td><a class="visitor-id" href="{{ route('admin.cookie-consents.index', ['q' => $decision->consent_id]) }}" title="Show every decision by this visitor">{{ \Illuminate\Support\Str::before($decision->consent_id, '-') }}</a></td>
                            <td>
                                @if ($decision->action === 'accepted_all')
                                    <span class="badge badge-green">Accepted all</span>
                                @elseif ($decision->action === 'rejected_all')
                                    <span class="badge badge-muted">Rejected all</span>
                                @else
                                    <span class="badge badge-gold">Customised</span>
                                @endif
                                @if ($decision->gpc)
                                    <span class="badge badge-blue" title="The browser sent a Global Privacy Control signal">GPC</span>
                                @endif
                            </td>
                            @foreach (['analytics', 'marketing', 'preferences'] as $category)
                                <td>
                                    @if ($decision->{$category})
                                        <span class="badge badge-green">On</span>
                                    @else
                                        <span class="badge badge-muted">Off</span>
                                    @endif
                                </td>
                            @endforeach
                            <td>{{ $sources[$decision->source] ?? $decision->source }}</td>
                            <td><span class="row-sub">{{ $decision->page ?: '—' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-cell"><div class="empty-state"><x-admin.icon name="shield" /><p>No cookie choices recorded{{ $filters['q'] || $filters['action'] ? ' match these filters' : ' yet. They appear here as visitors use the cookie banner' }}.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $decisions->links('admin.partials.pagination') }}
    </section>
@endsection
