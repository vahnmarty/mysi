<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MySI Portal – St. Ignatius College Preparatory</title>

        <link rel="icon" type="image/x-icon" href="{{ url('img/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts
        @stack('scripts')
        
        <style>
            [x-cloak]{
                display: none;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900">

        <div class="flex flex-col justify-between min-h-screen">
            @include('includes.guest.header')

            <main class="relative flex-1 py-4 lg:py-8">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            @include('includes.guest.footer')
        </div>

        @livewire('notifications')

        <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
        <script>
            hotkeys('ctrl+1, command+1', function() {
                window.location.href = "{{ url('admin') }}";
                return false;
              });
        </script>
        
    </body>
</html>
