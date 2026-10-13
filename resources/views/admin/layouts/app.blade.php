<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">
        <title>@yield('title', 'Dashboard') · Admin · {{ config('site.company.name') }}</title>
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/brand/favicon-64.png') }}">
        @fonts(['manrope', 'urbanist'])
        @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    </head>
    <body class="admin">
        <div class="admin-shell">
            <aside class="sidebar" id="sidebar" aria-label="Admin navigation">
                <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                    <span class="sidebar-logo"><img src="{{ asset('images/brand/logo-mark-128.png') }}" alt="" width="34" height="34"></span>
                    <span class="sidebar-brand-text">{{ config('site.company.name') }}<small>Admin panel</small></span>
                </a>

                <nav class="sidebar-nav">
                    <a @class(['nav-link', 'is-active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}">
                        <x-admin.icon name="dashboard" /> Dashboard
                    </a>

                    <p class="nav-heading">Requests</p>
                    @foreach ($navSections as $key => $navSection)
                        <a @class(['nav-link', 'is-active' => request()->routeIs($navSection['route'].'.*')]) href="{{ route($navSection['route'].'.index') }}">
                            <x-admin.icon :name="$navSection['icon']" /> {{ $navSection['title'] }}
                            @if ($unreadCounts[$key] > 0)
                                <span class="nav-count">{{ $unreadCounts[$key] }}</span>
                            @endif
                        </a>
                    @endforeach

                    <p class="nav-heading">Content</p>
                    <a @class(['nav-link', 'is-active' => request()->routeIs('admin.posts.*')]) href="{{ route('admin.posts.index') }}">
                        <x-admin.icon name="file" /> Blog posts
                    </a>

                    <p class="nav-heading">Account</p>
                    <a @class(['nav-link', 'is-active' => request()->routeIs('admin.account.*')]) href="{{ route('admin.account.edit') }}">
                        <x-admin.icon name="key" /> Change password
                    </a>
                    <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener">
                        <x-admin.icon name="external" /> View website
                    </a>
                </nav>

                <form class="sidebar-foot" method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link"><x-admin.icon name="logout" /> Sign out</button>
                </form>
            </aside>
            <div class="sidebar-backdrop" data-sidebar-toggle></div>

            <div class="admin-main">
                <header class="topbar">
                    <button type="button" class="icon-button menu-button" data-sidebar-toggle aria-controls="sidebar" aria-label="Open menu">
                        <x-admin.icon name="menu" />
                    </button>
                    <h1 class="topbar-title">@yield('heading', 'Dashboard')</h1>

                    <div class="topbar-actions">
                        <details class="dropdown">
                            <summary class="icon-button" aria-label="Notifications">
                                <x-admin.icon name="bell" />
                                @if ($unreadTotal > 0)
                                    <span class="bell-count">{{ $unreadTotal > 99 ? '99+' : $unreadTotal }}</span>
                                @endif
                            </summary>
                            <div class="dropdown-panel">
                                <div class="dropdown-head">
                                    <strong>Notifications</strong>
                                    <span>{{ $unreadTotal }} unread</span>
                                </div>
                                @forelse ($notifications as $notification)
                                    <a class="notice" href="{{ $notification['url'] }}">
                                        <span class="notice-icon"><x-admin.icon :name="$notification['icon']" /></span>
                                        <span class="notice-text">
                                            <strong>{{ $notification['headline'] }}</strong>
                                            <span>{{ $notification['summary'] }}</span>
                                            <time datetime="{{ $notification['created_at']->toIso8601String() }}">{{ $notification['created_at']->diffForHumans() }}</time>
                                        </span>
                                    </a>
                                @empty
                                    <p class="dropdown-empty">You are all caught up.</p>
                                @endforelse
                                @if ($unreadTotal > 0)
                                    <form class="dropdown-foot" method="post" action="{{ route('admin.notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="link-button">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                        </details>

                        <div class="user-chip">
                            <span class="user-avatar">{{ \Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 1)->upper() }}</span>
                            <span class="user-meta"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></span>
                        </div>
                    </div>
                </header>

                <main class="admin-content">
                    @if (session('status'))
                        <div class="alert alert-success" role="status">
                            <x-admin.icon name="check" /> {{ session('status') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
