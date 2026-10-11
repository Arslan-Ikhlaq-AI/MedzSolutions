<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $mode === 'dashboard' ? 'Dashboard' : ($mode === 'register' ? 'Create your account' : 'Sign in') }} · MedzSolutions</title>
        @fonts('instrument-sans')
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div
            id="app"
            data-mode="{{ $mode }}"
            data-name="{{ auth()->user()?->name ?? '' }}"
            data-email="{{ old('email', auth()->user()?->email ?? '') }}"
            data-errors="{{ json_encode($errors->messages()) }}"
            data-status="{{ session('status', '') }}"
        ></div>
    </body>
</html>
