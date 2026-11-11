<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Sawit Maju Makmur'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind / Vite -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <link rel="stylesheet" href="{{ asset('css/sawit.css') }}">
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
    @if (isset($slot))
        <div class="min-h-screen bg-gray-100">
            @if (auth()->check())
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if (session('success'))
                    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
                @if (session('info'))
                    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-200">
                            {{ session('info') }}
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    @else
        <nav class="navbar">
            <div class="inner">
                <a href="{{ route('assessments.index') }}" class="brand">
                    🌴 Sawit Maju Makmur <span>Self-Assessment</span>
                </a>
                <div>
                    <a class="link" href="{{ route('assessments.index') }}">Riwayat</a>
                    <a class="link" href="{{ route('assessments.create') }}">Isi Formulir</a>
                    @if (Route::has('lahan.index'))
                        <a class="link" href="{{ route('lahan.index') }}">Data Lahan</a>
                    @endif
                    @if (auth()->check())
                        <a class="link" href="{{ route('suppliers.index') }}">Profil Pemasok</a>
                        <a class="link" href="{{ route('dashboard') }}">Dashboard</a>
                    @endif
                </div>
            </div>
        </nav>

        <main class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" style="background:#fde8e8;color:#9b1c1c;padding:12px;border-radius:6px;margin-bottom:16px;">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>

        <footer>&copy; {{ date('Y') }} Sawit Maju Makmur · SCRUM-25</footer>
    @endif
    @stack('scripts')
</body>
</html>
