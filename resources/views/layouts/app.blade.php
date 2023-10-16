<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MySI Portal – {{ $title ?? 'St. Ignatius College Preparatory' }}</title>

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

<body class="h-full font-sans antialiased">

    @include('includes.portal.header')

    <div class="bg-white lg:flex">

        @include('includes.portal.sidebar')

        <div class="flex-1">
            <main class="px-4 py-3 lg:px-16 lg:py-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @include('includes.portal.footer')

    @include('includes.portal.page-loader')

    @livewire('notifications')

    <script>

        Livewire.on('leftAsterisk', event => {
            setTimeout(() => {
                leftAsterisk();
            }, 100);
        })


        leftAsterisk();

        function leftAsterisk()
        {
            
            //const labels = document.querySelectorAll('.filament-forms-field-wrapper-label');
        
            //labels.forEach(label => {
            //    const domSpan = label.querySelector('span');
            //    if(domSpan.querySelector('sup') !== null){
            //        label.innerHTML = `<span class="-mr-2 font-medium text-primary-red">*</span>${label.innerHTML}`;
            //    }
            //});
        }
        
    </script>

</body>

</html>
