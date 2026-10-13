@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h2>Welcome back, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h2>
            <p class="muted">
                @if ($unreadTotal > 0)
                    You have <strong>{{ $unreadTotal }}</strong> unread {{ \Illuminate\Support\Str::plural('request', $unreadTotal) }} waiting for you.
                @else
                    Everything is read. {{ $weekTotal }} {{ \Illuminate\Support\Str::plural('request', $weekTotal) }} came in over the last 7 days.
                @endif
            </p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.posts.create') }}"><x-admin.icon name="plus" /> New blog post</a>
    </div>

    <div class="stat-grid">
        @foreach ($sections as $key => $section)
            <a class="stat-card" href="{{ route($section['route'].'.index') }}">
                <span class="stat-icon"><x-admin.icon :name="$section['icon']" /></span>
                <span class="stat-body">
                    <span class="stat-label">{{ $section['title'] }}</span>
                    <span class="stat-value">{{ $section['total'] }}</span>
                    <span class="stat-meta">
                        @if ($section['unread'] > 0)
                            <span class="badge badge-blue">{{ $section['unread'] }} new</span>
                        @else
                            <span class="badge badge-muted">All read</span>
                        @endif
                        <span>{{ $section['this_week'] }} this week</span>
                    </span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="dashboard-grid">
        <section class="card">
            <div class="card-head">
                <h3>Requests over the last 14 days</h3>
                <span class="muted">{{ collect($daily)->sum('count') }} total</span>
            </div>
            <div class="chart" role="img" aria-label="Requests received per day for the last 14 days">
                @foreach ($daily as $day)
                    <div class="chart-col" title="{{ $day['date']->format('M j') }}: {{ $day['count'] }}">
                        <span class="chart-value">{{ $day['count'] ?: '' }}</span>
                        <span class="chart-bar" style="height: {{ max(3, round($day['count'] / $dailyMax * 100)) }}%"></span>
                        <span class="chart-label">{{ $day['date']->format('j') }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card">
            <div class="card-head"><h3>Blog</h3><a class="text-link" href="{{ route('admin.posts.index') }}">Manage</a></div>
            <dl class="facts">
                <div><dt>Published posts</dt><dd>{{ $posts['published'] }}</dd></div>
                <div><dt>Drafts</dt><dd>{{ $posts['drafts'] }}</dd></div>
            </dl>
            <a class="btn btn-ghost btn-block" href="{{ route('admin.posts.create') }}"><x-admin.icon name="plus" /> Write a post</a>
        </section>
    </div>

    <section class="card">
        <div class="card-head">
            <h3>Recent activity</h3>
            @if ($unreadTotal > 0)
                <form method="post" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="link-button">Mark all as read</button>
                </form>
            @endif
        </div>
        <ul class="activity">
            @forelse ($recent as $item)
                <li>
                    <a @class(['activity-item', 'is-unread' => $item['unread']]) href="{{ $item['url'] }}">
                        <span class="activity-icon"><x-admin.icon :name="$item['icon']" /></span>
                        <span class="activity-text">
                            <strong>{{ $item['headline'] }}</strong>
                            <span>{{ $item['summary'] }}</span>
                        </span>
                        <span class="activity-meta">
                            <span class="badge badge-muted">{{ $item['section'] }}</span>
                            <time datetime="{{ $item['created_at']->toIso8601String() }}" title="{{ $item['created_at']->format('M j, Y g:i A') }}">{{ $item['created_at']->diffForHumans() }}</time>
                        </span>
                    </a>
                </li>
            @empty
                <li class="empty-state">
                    <x-admin.icon name="inbox" />
                    <p>No requests yet. Messages, demo requests and applications from the website will appear here.</p>
                </li>
            @endforelse
        </ul>
    </section>
@endsection
