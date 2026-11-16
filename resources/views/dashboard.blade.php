<x-app-layout title="Dashboard">
    @php
        $user = auth()->user();
    @endphp

    <div class="dashboard-header">
        <div>
            <p class="eyebrow">Portal Utama</p>
            <h1 class="dashboard-title">Dashboard</h1>
        </div>
        <span class="status-badge">Online</span>
    </div>

    <section class="action-card">
        <h2 class="card-title">Selamat datang, {{ $user->name }}</h2>
        <p style="margin:0 0 1rem; color:#475c53; line-height:1.7;">
            Dashboard Anda diarahkan ke ruang kerja sesuai peran. Jika tidak diarahkan otomatis, buka profil untuk memeriksa informasi akun dan akses.
        </p>
        <a href="{{ route('profile.edit') }}" class="pill-link">Kelola profil</a>
    </section>
</x-app-layout>
