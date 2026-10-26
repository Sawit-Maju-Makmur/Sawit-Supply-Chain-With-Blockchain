<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</body>
</html>
