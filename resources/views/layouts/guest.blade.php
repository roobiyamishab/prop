<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Optional: keep Breeze assets if you still need them elsewhere --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Your AIPropMatch font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        {{-- Your custom CSS from public assets --}}
        <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
        {{-- If your file is inside /public/assets, use: asset('assets/style.css') --}}
    </head>
    <body class="font-sans antialiased">
        {{-- Let your own HTML/CSS control the layout --}}
        <div id="app">
            {{ $slot }}
        </div>

        {{-- Your custom JS from public assets (optional) --}}
        <script src="{{ asset('assets/script.js') }}"></script>
        {{-- Or asset('assets/script.js') if that’s the path --}}
    </body>
</html>
