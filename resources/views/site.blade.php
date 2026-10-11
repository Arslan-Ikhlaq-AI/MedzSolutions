<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title }} – {{ config('site.company.name') }}</title>
        <meta name="description" content="{{ $description }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('site.company.name') }}">
        <meta property="og:title" content="{{ $title }} – {{ config('site.company.name') }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/brand/og-image.jpg') }}">
        <meta name="theme-color" content="#16457a">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/brand/favicon-64.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
        @fonts(['manrope', 'urbanist', 'outfit'])
        @viteReactRefresh
        @vite(['resources/css/site.css', 'resources/js/site.js'])
        <noscript><style>.preloader { display: none; }</style></noscript>
    </head>
    <body>
        <div class="preloader" id="preloader" aria-hidden="true">
            <div class="preloader-spinner">
                <span class="preloader-ring"></span>
                <span class="preloader-badge">
                    <img src="{{ asset('images/brand/logo-mark-128.png') }}" alt="" width="76" height="77">
                </span>
            </div>
        </div>

        <div id="site"></div>

        <noscript>
            <p class="noscript-note">Please enable JavaScript to browse the {{ config('site.company.name') }} website.</p>
        </noscript>

        <script>
            window.siteData = {{ Js::from([
                'page' => $page,
                'props' => $props,
                'shared' => $shared,
                'flash' => [
                    'form' => session('form'),
                    'status' => session('status'),
                    'errors' => $errors->messages(),
                    'old' => old(),
                ],
                'csrfToken' => csrf_token(),
            ]) }};
        </script>
    </body>
</html>
