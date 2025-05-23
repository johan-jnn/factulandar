<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Factulandar - {{ $title }}
    </title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="/icon.svg" type="image/svg">

    <x-use-toasts />
    @if (session('message'))
        <x-toast text="{{ session('message') }}" />
    @endif
    @error('message')
        <x-toast text="{{ $message }}" type="error" />
    @enderror

    @yield('head')
</head>

<body>
    <x-header :page-title="$title" :logo-url="$indexURL ?? null" />
    @yield('body')
</body>

</html>
