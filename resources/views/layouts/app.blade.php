<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Self-Assessment') - Sawit Maju Makmur</title>
    <link rel="stylesheet" href="{{ asset('css/sawit.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pastikan reset tailwind tidak merusak navbar bawaan sawit */
        .navbar .brand span { opacity: .8; font-weight: 400; font-size: .85rem; margin-left: 6px; }
        .navbar a.link { color: #fff; text-decoration: none; margin-left: 18px; opacity: .9; }
        .navbar a.link:hover { opacity: 1; text-decoration: underline; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="inner">
            <a href="{{ route('assessments.index') }}" class="brand">
                🌴 Sawit Maju Makmur <span>Supply Chain</span>
            </a>
            <div style="display:flex; align-items:center; gap:12px;">
                <a class="link" href="{{ route('assessments.index') }}">Self-Assessment</a>
                @auth
                    @if (auth()->user()->role === 'management' || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('management')))
                        <a class="link" href="{{ route('management.suppliers.index') }}" style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px; font-weight: 600;">Data Pemasok (PBI 9)</a>
                    @endif
                    <a class="link" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="link" style="background:none; border:none; cursor:pointer; color:#fff; font:inherit; margin-left:14px; opacity:0.85;">Keluar</button>
                    </form>
                @else
                    <a class="link" href="{{ route('login') }}" style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 6px; font-weight: 600;">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer style="text-align:center; padding: 20px; color: #5f7566; font-size: 0.85rem;">
        &copy; {{ date('Y') }} Sawit Maju Makmur · SCRUM-25
    </footer>
</body>
</html>
