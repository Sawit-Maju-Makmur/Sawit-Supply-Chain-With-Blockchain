<x-app-layout title="Dashboard Pemasok">
    <div class="dashboard-header">
        <div>
            <p class="eyebrow">Portal Pemasok</p>
            <h1 class="dashboard-title">Dashboard Pemasok</h1>
        </div>
        <span class="status-badge">Online</span>
    </div>

    @if (session('status'))
        <div class="banner">{{ session('status') }}</div>
    @endif

    <div class="stats-grid">
        <article class="stat-card">
            <p class="stat-label">Lahan pemasok</p>
            <p class="stat-value">{{ $plotCount }}</p>
            <div class="stat-meta">Lahan terdaftar di akun Anda</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Peta lahan</p>
            <p class="stat-value">{{ $landAreaCount }}</p>
            <div class="stat-meta">Batas lahan tersimpan di akun Anda</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Dokumen</p>
            <p class="stat-value">{{ $documentCount }}</p>
            <div class="stat-meta">Dokumen pada lahan pemasok Anda</div>
        </article>
        <article class="stat-card">
            <p class="stat-label">Menunggu review</p>
            <p class="stat-value">{{ $pendingDocumentCount }}</p>
            <div class="stat-meta">Dokumen yang masih menunggu</div>
        </article>
    </div>

    <div class="grid-panels">
        <section class="action-card">
            <h2 class="card-title">Akses cepat</h2>
            <div class="action-list">
                <div class="action-item">
                    <div>
                        <span class="action-name">Profil Usaha</span>
                        <span class="action-sub">Kelola identitas usaha, NIK, NPWP, dan NIB</span>
                    </div>
                    <a href="{{ route('pemasok.profile.edit') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Kelola lahan</span>
                        <span class="action-sub">Tambah dan perbarui data kebun serta peta</span>
                    </div>
                    <a href="{{ route('lahan.index') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Dokumen legalitas</span>
                        <span class="action-sub">Unggah sertifikasi dan pantau hasil pemeriksaan</span>
                    </div>
                    <a href="{{ route('pemasok.documents.index') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Self-Assessment</span>
                        <span class="action-sub">Evaluasi mandiri sustainability & ketertelusuran</span>
                    </div>
                    <a href="{{ route('assessments.index') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Peta batas lahan</span>
                        <span class="action-sub">Simpan poligon lahan pada akun Anda</span>
                    </div>
                    <a href="{{ route('land-areas.create') }}" class="pill-link">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Hasil Panen & QR Code 🌾</span>
                        <span class="action-sub">Catat panen & terbitkan QR Code blockchain</span>
                    </div>
                    <a href="{{ route('pemasok.harvests.index') }}" class="pill-link" style="background:#16a34a;">Buka</a>
                </div>
                <div class="action-item">
                    <div>
                        <span class="action-name">Profil</span>
                        <span class="action-sub">Kelola data akun dan kontak</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="pill-link">Buka</a>
                </div>
            </div>
        </section>

        <aside class="action-card">
            <h2 class="card-title">Ringkasan akun</h2>
            <p style="margin:0; color:#475c53; line-height:1.7;">
                Halo <strong>{{ auth()->user()->name }}</strong>, ringkasan ini menampilkan plot, peta lahan, dan dokumen yang terhubung dengan akun Anda.
                @if (! $supplier)
                    Data pemasok Anda belum ditautkan. Hubungi pengelola untuk menghubungkan akun dengan data pemasok.
                @endif
            </p>
        </aside>
    </div>

    <div class="grid-panels" style="margin-top: 1.25rem;">
        <section class="action-card">
            <h2 class="card-title">Lahan terbaru</h2>
            @if ($recentPlots->isEmpty())
                <p style="margin:0; color:#475c53;">Belum ada lahan yang terhubung ke akun Anda.</p>
            @else
                <div class="action-list">
                    @foreach ($recentPlots as $plot)
                        <div class="action-item">
                            <div>
                                <span class="action-name">{{ $plot->nama_kebun ?? $plot->name }}</span>
                                <span class="action-sub">{{ $plot->lokasi ?? $plot->regency ?? 'Kabupaten belum diisi' }} · {{ number_format((float) ($plot->luas_ha ?? $plot->area_ha), 2, ',', '.') }} ha</span>
                            </div>
                            <span class="action-sub">{{ \App\Models\Lahan::STATUS[$plot->status_lahan ?? ''] ?? \App\Models\Plot::OWNERSHIP[$plot->ownership_status ?? ''] ?? ($plot->status_lahan ?? $plot->ownership_status ?? '-') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="action-card">
            <h2 class="card-title">Peta lahan terbaru</h2>
            @if ($recentLandAreas->isEmpty())
                <p style="margin:0; color:#475c53;">Belum ada poligon lahan yang tersimpan di akun Anda.</p>
            @else
                <div class="action-list">
                    @foreach ($recentLandAreas as $landArea)
                        <div class="action-item">
                            <div>
                                <span class="action-name">{{ $landArea->name }}</span>
                                <span class="action-sub">Diperbarui {{ $landArea->updated_at->diffForHumans() }}</span>
                            </div>
                            <span class="record-badge">{{ count($landArea->polygon_coordinates ?? []) }} titik</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </aside>
    </div>

    <section class="action-card" style="margin-top: 1.25rem;">
        <h2 class="card-title">Dokumen terbaru</h2>
        @if ($recentDocuments->isEmpty())
            <p style="margin:0; color:#475c53;">Belum ada dokumen pada lahan yang terhubung ke akun Anda.</p>
        @else
            <div class="action-list">
                @foreach ($recentDocuments as $document)
                    <div class="action-item">
                        <div>
                            <span class="action-name">{{ $document->type }}</span>
                            <span class="action-sub">{{ $document->lahan?->nama_kebun ?? $document->plot?->name ?? 'Lahan tidak tersedia' }}</span>
                        </div>
                        <span class="record-badge" data-status="{{ $document->status }}">{{ \App\Models\Document::STATUSES[$document->status] ?? ucfirst($document->status) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</x-app-layout>
