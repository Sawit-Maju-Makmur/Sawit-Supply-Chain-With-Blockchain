<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Sawit Maju Makmur'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if (file_exists(public_path('css/sawit.css')))
            <link rel="stylesheet" href="{{ asset('css/sawit.css') }}">
        @endif
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-800 min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
            @if (session('success'))
                <div class="p-4 mb-3 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-3 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 w-full">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <footer class="text-center py-6 text-xs text-gray-500 border-t border-gray-200 bg-white">
            &copy; {{ date('Y') }} Sawit Maju Makmur &mdash; Supply Chain Traceability Platform
        </footer>

        @stack('scripts')
    </body>
</html>
