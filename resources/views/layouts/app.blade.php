<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIPS Sawit') - Sawit Maju Makmur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --sips-bg: #e8ffef;
            --sips-surface: #ffffff;
            --sips-surface-soft: #f1faf5;
            --sips-primary: #00603a;
            --sips-primary-strong: #0b4f35;
            --sips-primary-soft: #dff8e8;
            --sips-border: #dfeae3;
            --sips-text: #123427;
            --sips-muted: #5a6f65;
            --sips-warning: #fef3c7;
            --sips-warning-text: #92400e;
            --sips-success: #dcfce7;
            --sips-success-text: #166534;
            --sips-error: #fee2e2;
            --sips-error-text: #991b1b;
            --shadow-soft: 0 18px 40px rgba(15, 53, 36, 0.08);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(180deg, #edfdf1 0%, #f8fffb 100%);
            color: var(--sips-text);
        }

        a { text-decoration: none; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: linear-gradient(135deg, var(--sips-primary) 0%, var(--sips-primary-strong) 100%);
            box-shadow: 0 10px 30px rgba(0, 96, 58, 0.2);
        }

        .topbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand-mark {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.85rem;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .brand small {
            display: block;
            opacity: 0.8;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .nav-link,
        .nav-button {
            border-radius: 9999px;
            padding: 0.55rem 0.9rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.08);
        }

        .nav-link:hover,
        .nav-button:hover {
            background: rgba(255,255,255,0.16);
            transform: translateY(-1px);
        }

        .nav-button {
            border: 0;
            cursor: pointer;
            color: white;
            background: rgba(255,255,255,0.08);
        }

        .page-shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.25rem 3rem;
        }

        .page-card {
            background: rgba(255,255,255,0.86);
            backdrop-filter: blur(8px);
            border: 1px solid var(--sips-border);
            border-radius: 1.5rem;
            box-shadow: var(--shadow-soft);
            padding: 1.5rem;
        }

        .alert {
            border-radius: 1rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--sips-success);
            color: var(--sips-success-text);
            border-color: #bbf7d0;
        }

        .alert-error {
            background: var(--sips-error);
            color: var(--sips-error-text);
            border-color: #fecaca;
        }

        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--sips-border);
        }

        .eyebrow {
            margin: 0 0 0.35rem;
            color: var(--sips-primary);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .dashboard-title {
            margin: 0;
            font-size: clamp(1.8rem, 2vw, 2.5rem);
            line-height: 1.2;
            letter-spacing: -0.04em;
            color: var(--sips-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--sips-primary-soft);
            color: var(--sips-primary);
            border: 1px solid rgba(0, 96, 58, 0.12);
            border-radius: 999px;
            padding: 0.5rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .status-badge::before {
            content: "";
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
        }

        .record-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: 1px solid #dfeae3;
            border-radius: 999px;
            padding: 0.35rem 0.65rem;
            color: #475c53;
            background: #f1f5f2;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .record-badge::before {
            content: "";
            width: 0.42rem;
            height: 0.42rem;
            border-radius: 999px;
            background: #64748b;
        }

        .record-badge[data-status="active"]::before,
        .record-badge[data-status="approved"]::before { background: #16a34a; }

        .record-badge[data-status="pending"]::before,
        .record-badge[data-status="draft"]::before { background: #d97706; }

        .record-badge[data-status="inactive"]::before,
        .record-badge[data-status="rejected"]::before { background: #dc2626; }

        .banner {
            background: #fff7d6;
            color: var(--sips-warning-text);
            border: 1px solid #f3d9a5;
            border-radius: 1rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card, .action-card {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
            border: 1px solid var(--sips-border);
            border-radius: 1.2rem;
            box-shadow: 0 8px 25px rgba(10, 42, 31, 0.04);
        }

        .stat-card {
            padding: 1.2rem 1.1rem;
        }

        .stat-label {
            color: var(--sips-muted);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0 0 0.6rem;
        }

        .stat-value {
            font-size: clamp(1.6rem, 2vw, 2.2rem);
            font-weight: 800;
            letter-spacing: -0.05em;
            margin: 0;
            color: var(--sips-primary-strong);
        }

        .stat-meta {
            font-size: 0.82rem;
            color: var(--sips-muted);
            margin-top: 0.5rem;
        }

        .grid-panels {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 1.25rem;
        }

        .action-card {
            padding: 1.2rem;
        }

        .card-title {
            margin: 0 0 0.9rem;
            font-size: 1.15rem;
            font-weight: 800;
        }

        .action-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.8rem 0.9rem;
            border-radius: 0.9rem;
            border: 1px solid var(--sips-border);
            background: #f9fdfb;
        }

        .action-name {
            font-weight: 700;
            color: var(--sips-text);
        }

        .action-sub {
            display: block;
            font-size: 0.8rem;
            color: var(--sips-muted);
            margin-top: 0.2rem;
        }

        .pill-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 0.9rem;
            border-radius: 999px;
            background: var(--sips-primary);
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            transition: opacity 0.2s ease;
        }

        .pill-link:hover { opacity: 0.92; }

        @media (max-width: 768px) {
            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .grid-panels {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark">🌴</span>
                <span>
                    Sawit Maju Makmur
                    <small>Supply Chain</small>
                </span>
            </a>

            <div class="nav-actions">
                @auth
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    @if (auth()->user()->role === 'pemasok' || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('pemasok')))
                        <a class="nav-link" href="{{ route('lahan.index') }}">Lahan</a>
                        <a class="nav-link" href="{{ route('land-areas.create') }}">Peta lahan</a>
                    @endif
                    @if (auth()->user()->role === 'management' || auth()->user()->role === 'sustainability_officer' || (method_exists(auth()->user(), 'hasRole') && (auth()->user()->hasRole('management') || auth()->user()->hasRole('sustainability_officer'))))
                        <a class="nav-link" href="{{ route('management.suppliers.index') }}">Data Pemasok (PBI 9)</a>
                    @endif
                    <a class="nav-link" href="{{ route('profile.edit') }}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="nav-button">Keluar</button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="page-shell">
        <div class="page-card">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </main>

    @stack('scripts')
</body>
</html>
