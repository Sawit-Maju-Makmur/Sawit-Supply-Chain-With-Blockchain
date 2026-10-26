<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</body>
</html>