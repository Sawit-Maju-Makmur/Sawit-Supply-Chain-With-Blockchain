<x-app-layout title="Dashboard Sustainability Officer">
    <div class="dashboard-header">
        <div>
            <p class="eyebrow">Portal Officer</p>
            <h1 class="dashboard-title">Dashboard Sustainability Officer</h1>
        </div>
        <span class="status-badge">Online</span>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <p class="stat-label">Pemasok</p>
            <p class="stat-value">{{ $supplierCount }}</p>
            <div class="stat-meta">Pemasok terdaftar</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Pemasok aktif</p>
            <p class="stat-value">{{ $activeSupplierCount }}</p>
            <div class="stat-meta">Status aktif dengan sertifikasi valid</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Plot</p>
            <p class="stat-value">{{ $plotCount }}</p>
            <div class="stat-meta">Plot terhubung ke pemasok</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Dokumen menunggu</p>
            <p class="stat-value">{{ $pendingDocumentCount }}</p>
            <div class="stat-meta">Perlu ditindaklanjuti</div>
        </article>
    </div>

    <div class="grid-panels">
        <section class="action-card">
            <h2 class="card-title">Akses cepat</h2>
            <div class="action-list">
                <div class="action-item">
                    <div>
                        <span class="action-name">Data pemasok</span>
                        <span class="action-sub">Tinjau profil, plot, dan status pemasok</span>
                    </div>
                    <a href="{{ route('management.suppliers.index') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Review dokumen legalitas</span>
                        <span class="action-sub">Setujui atau tolak dokumen pemasok</span>
                    </div>
                    <a href="{{ route('officer.documents.index') }}" class="pill-link">Review</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Kriteria dan bobot asesmen</span>
                        <span class="action-sub">Atur parameter Sustainability dan Traceability</span>
                    </div>
                    <a href="{{ route('officer.criteria.index') }}" class="pill-link">Atur</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Profil</span>
                        <span class="action-sub">Kelola informasi akun Anda</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="pill-link">Buka</a>
                </div>
            </div>
        </section>

        <aside class="action-card">
            <h2 class="card-title">Prioritas review</h2>
            <div class="action-list">
                <div class="action-item">
                    <div>
                        <span class="action-name">Dokumen ditolak</span>
                        <span class="action-sub">{{ $rejectedDocumentCount }} dokumen perlu tindak lanjut pemasok</span>
                    </div>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Dokumen menunggu</span>
                        <span class="action-sub">{{ $pendingDocumentCount }} dokumen menunggu pemeriksaan</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <div class="grid-panels" style="margin-top: 1.25rem;">
        <section class="action-card">
            <h2 class="card-title">Pemasok terbaru</h2>
            @if ($recentSuppliers->isEmpty())
                <p style="margin:0; color:#475c53;">Belum ada pemasok yang tercatat.</p>
            @else
                <div class="action-list">
                    @foreach ($recentSuppliers as $supplier)
                        <a href="{{ route('management.suppliers.show', $supplier) }}" class="action-item">
                            <span>
                                <span class="action-name">{{ $supplier->name }}</span>
                                <span class="action-sub">{{ $supplier->email ?: 'Email belum tersedia' }}</span>
                            </span>
                            <span class="record-badge" data-status="{{ $supplier->status }}">{{ $supplier->status_label }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="action-card">
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
        </aside>
    </div>
</x-app-layout>
