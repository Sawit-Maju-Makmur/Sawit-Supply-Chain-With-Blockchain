<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
    <title>@yield('title', 'Self-Assessment') - Sawit Maju Makmur</title>
    <link rel="stylesheet" href="{{ asset('css/sawit.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="inner">
            <a href="{{ route('assessments.index') }}" class="brand">
                🌴 Sawit Maju Makmur <span>Self-Assessment</span>
            </a>
            <div>
                <a class="link" href="{{ route('assessments.index') }}">Riwayat</a>
                <a class="link" href="{{ route('assessments.create') }}">Isi Formulir</a>
            </div>
        </div>
    </nav>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>&copy; {{ date('Y') }} Sawit Maju Makmur · SCRUM-25</footer>
=======
    <title>Sawit Maju Makmur - Data Lahan</title>
    @stack('styles')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-green-700 text-white px-6 py-3 font-semibold">
        Sawit Maju Makmur &mdash; Data Lahan Pemasok
    </nav>
    <main class="max-w-5xl mx-auto p-6">
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
>>>>>>> f017a484a6c468e69098875f0dd98e4f15c46ac3
</body>
</html>
