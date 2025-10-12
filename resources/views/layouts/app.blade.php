<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Yapping') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body class="font-sans antialiased bg-[#f9fafc] text-gray-800">
        <x-banner />

        <div class="min-h-screen flex flex-col bg-gradient-to-br from-[#f9fafc] via-white to-[#f4f0f9]">
            <!-- Navigation -->
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 text-center py-6 text-sm text-gray-500">
                © {{ date('Y') }} Yapping. All rights reserved.
            </footer>
        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>
