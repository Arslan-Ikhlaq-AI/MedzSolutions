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
        {{-- Google Consent Mode v2: everything optional is denied until the visitor agrees. Runs before any tag. --}}
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { window.dataLayer.push(arguments); }
            (function () {
                var saved = {};
                try {
                    var match = document.cookie.match(/(?:^|; )medz_consent=([^;]*)/);
                    var parsed = match ? JSON.parse(decodeURIComponent(match[1])) : null;
                    if (parsed && parsed.v === @js($consentVersion)) { saved = parsed; }
                } catch (error) {}
                var state = function (granted) { return granted ? 'granted' : 'denied'; };
                gtag('consent', 'default', {
                    ad_storage: state(saved.m),
                    ad_user_data: state(saved.m),
                    ad_personalization: state(saved.m),
                    analytics_storage: state(saved.a),
                    personalization_storage: state(saved.p),
                    functionality_storage: 'granted',
                    security_storage: 'granted',
                    wait_for_update: 500
                });
            })();
        </script>
        {{-- Optional tools stay inert (type="text/plain") until the visitor allows their category. --}}
        @isset($trackingIntegrations['google_analytics'])
            <script type="text/plain" data-consent="analytics" data-src="https://www.googletagmanager.com/gtag/js?id={{ $trackingIntegrations['google_analytics'] }}" async></script>
            <script type="text/plain" data-consent="analytics">gtag('js', new Date()); gtag('config', @js($trackingIntegrations['google_analytics']));</script>
        @endisset
        @isset($trackingIntegrations['meta_pixel'])
            <script type="text/plain" data-consent="marketing">
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', @js($trackingIntegrations['meta_pixel']));
                fbq('track', 'PageView');
            </script>
        @endisset
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
