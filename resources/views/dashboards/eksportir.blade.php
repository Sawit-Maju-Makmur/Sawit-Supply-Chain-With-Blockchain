<x-app-layout title="Dashboard Eksportir">
    <div class="dashboard-header">
        <div>
            <p class="eyebrow">Portal Ekspor</p>
            <h1 class="dashboard-title">Dashboard Eksportir</h1>
        </div>
        <span class="status-badge">Online</span>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <p class="stat-label">Dokumen</p>
            <p class="stat-value">{{ $documentCount }}</p>
            <div class="stat-meta">Dokumen rantai pasok pada sistem</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Menunggu review</p>
            <p class="stat-value">{{ $pendingDocumentCount }}</p>
            <div class="stat-meta">Dokumen belum disetujui</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Plot</p>
            <p class="stat-value">{{ $plotCount }}</p>
            <div class="stat-meta">Plot pemasok terdaftar</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Pemasok aktif</p>
            <p class="stat-value">{{ $activeSupplierCount }}</p>
            <div class="stat-meta">Pemasok aktif di sistem</div>
        </article>
    </div>

    <div class="grid-panels">
        <section class="action-card">
            <h2 class="card-title">Dokumen terbaru</h2>
            @if ($recentDocuments->isEmpty())
                <p style="margin:0; color:#475c53;">Belum ada dokumen yang tercatat.</p>
            @else
                <div class="action-list">
                    @foreach ($recentDocuments as $document)
                        <div class="action-item">
                            <div>
                                <span class="action-name">{{ $document->type }}</span>
                                <span class="action-sub">{{ $document->plot?->supplier?->name ?? $document->plot?->name ?? 'Pemasok tidak tersedia' }}</span>
                            </div>
                            <span class="record-badge" data-status="{{ $document->status }}">{{ \App\Models\Document::STATUSES[$document->status] ?? ucfirst($document->status) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="action-card">
            <h2 class="card-title">Akun dan ruang kerja</h2>
            <p style="margin:0 0 1rem; color:#475c53; line-height:1.7;">
                Halo <strong>{{ auth()->user()->name }}</strong>. Metrik di halaman ini merupakan ringkasan data rantai pasok pada sistem, bukan hanya data milik satu eksportir.
            </p>
            <a href="{{ route('profile.edit') }}" class="pill-link">Kelola profil</a>
        </aside>
    </div>
</x-app-layout>
