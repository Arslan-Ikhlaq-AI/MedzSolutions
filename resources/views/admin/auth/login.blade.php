<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Sign in · Admin · {{ config('site.company.name') }}</title>
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/brand/favicon-64.png') }}">
        @fonts(['manrope', 'urbanist'])
        @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    </head>
    <body class="admin login-page">
        <main class="login-grid">
            <section class="login-aside">
                <a class="sidebar-brand" href="{{ route('home') }}">
                    <span class="sidebar-logo"><img src="{{ asset('images/brand/logo-mark-128.png') }}" alt="" width="34" height="34"></span>
                    <span class="sidebar-brand-text">{{ config('site.company.name') }}<small>{{ config('site.company.tagline') }}</small></span>
                </a>
                <div class="login-aside-copy">
                    <h2>Everything your website receives, in one place.</h2>
                    <ul>
                        <li>Contact messages and demo requests</li>
                        <li>Job applications with CVs</li>
                        <li>Affiliate applications</li>
                        <li>Blog publishing</li>
                    </ul>
                </div>
                <p class="login-aside-foot">© {{ now()->year }} {{ config('site.company.name') }}</p>
            </section>

            <section class="login-form-wrap">
                <form class="login-card" method="post" action="{{ route('admin.login.store') }}">
                    @csrf
                    <h1>Admin sign in</h1>
                    <p class="muted">Use your admin account to manage website requests and blog posts.</p>

                    @if (session('status'))
                        <div class="alert alert-success" role="status"><x-admin.icon name="check" /> {{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <label class="field">
                        <span class="field-label">Email address</span>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </label>
                    <label class="field">
                        <span class="field-label">Password</span>
                        <input type="password" name="password" autocomplete="current-password" required>
                    </label>
                    <label class="check">
                        <input type="checkbox" name="remember" value="1"> Keep me signed in on this device
                    </label>
                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                    <a class="back-link" href="{{ route('home') }}">← Back to website</a>
                </form>
            </section>
        </main>
    </body>
</html>
