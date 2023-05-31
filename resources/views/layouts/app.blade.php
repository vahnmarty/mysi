<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<body class="h-full font-sans antialiased">

    @include('includes.portal.header')

    <div class="flex">

        @include('includes.portal.sidebar')

        <div class="flex-1 max-h-screen overflow-auto">
            <main class="lg:px-16 lg:py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('includes.portal.footer')

    @livewire('notifications')

</body>

</html>
