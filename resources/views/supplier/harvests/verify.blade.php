@extends('layouts.app')

@section('title', 'Verifikasi Panen – ' . $harvest->kode_panen)

@push('styles')
<style>
    .verify-hero {
        text-align: center;
        padding: 2rem 1rem 1.5rem;
        border-bottom: 1px solid var(--sips-border);
        margin-bottom: 1.5rem;
    }
    .verify-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.2rem;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    .verify-badge.confirmed { background: #dcfce7; color: #166534; }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .detail-cell {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--sips-border);
        font-size: 0.9rem;
    }
    .detail-cell:nth-child(odd) { border-right: 1px solid var(--sips-border); }
    .detail-cell-label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--sips-muted);
        margin-bottom: 0.3rem;
        font-weight: 700;
    }
    .detail-cell-value { font-weight: 700; color: var(--sips-text); }
    .hash-box {
        font-family: monospace;
        font-size: 0.78rem;
        word-break: break-all;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 0.6rem 0.8rem;
        color: #334155;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')

{{-- ── Hero verifikasi ───────────────────────────────────────────── --}}
<div class="verify-hero">
    <div style="font-size:3rem; margin-bottom:0.75rem;">🌴</div>
    <h1 style="margin:0 0 0.5rem; font-size:1.75rem; letter-spacing:-0.04em;">Verifikasi Hasil Panen</h1>
    <p style="margin:0 0 1rem; color:var(--sips-muted); font-size:0.95rem;">
        Data berikut telah tercatat dan diverifikasi di blockchain Sawit Maju Makmur.
    </p>

    @if ($harvest->blockchain_status === 'confirmed')
        <div class="verify-badge confirmed">
            ✅ Terverifikasi di Blockchain
        </div>
    @else
        <div class="verify-badge" style="background:#fef9c3; color:#92400e;">
            ⏳ Status: {{ $harvest->blockchain_status_label }}
        </div>
    @endif

    <p style="margin:0.5rem 0 0; font-size:0.82rem; color:var(--sips-muted);">
        Kode Panen:
        <strong style="font-family:monospace; color:var(--sips-primary);">{{ $harvest->kode_panen }}</strong>
    </p>
</div>

{{-- ── Grid detail ──────────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; align-items:start; margin-bottom:1.5rem;">

    {{-- Detail Muatan --}}
    <section class="action-card">
        <h2 class="card-title">📋 Detail Muatan</h2>
        <div class="detail-grid">
            <div class="detail-cell">
                <span class="detail-cell-label">Jenis Komoditas</span>
                <span class="detail-cell-value">{{ $harvest->jenis_komoditas }}</span>
            </div>
            <div class="detail-cell">
                <span class="detail-cell-label">Jumlah</span>
                <span class="detail-cell-value">{{ number_format($harvest->jumlah_kg, 2, ',', '.') }} kg</span>
            </div>
            <div class="detail-cell">
                <span class="detail-cell-label">Tanggal Panen</span>
                <span class="detail-cell-value">{{ $harvest->tanggal_panen->format('d M Y') }}</span>
            </div>
            <div class="detail-cell">
                <span class="detail-cell-label">Lahan / Kebun</span>
                <span class="detail-cell-value">{{ $harvest->lahan?->nama_kebun ?? '-' }}</span>
            </div>
            <div class="detail-cell" style="grid-column:1 / -1; border-right:none;">
                <span class="detail-cell-label">Lokasi Lahan</span>
                <span class="detail-cell-value">{{ $harvest->lahan?->lokasi ?? '-' }}</span>
            </div>
        </div>
    </section>

    {{-- Informasi Pemasok --}}
    <section class="action-card">
        <h2 class="card-title">🏭 Informasi Pemasok</h2>
        <div class="detail-grid">
            <div class="detail-cell" style="grid-column:1 / -1; border-right:none;">
                <span class="detail-cell-label">Nama Pemasok</span>
                <span class="detail-cell-value">{{ $harvest->supplier?->name ?? '-' }}</span>
            </div>
            <div class="detail-cell">
                <span class="detail-cell-label">Tipe Usaha</span>
                <span class="detail-cell-value">{{ $harvest->supplier?->type_label ?? '-' }}</span>
            </div>
            <div class="detail-cell">
                <span class="detail-cell-label">Luas Lahan</span>
                <span class="detail-cell-value">{{ number_format($harvest->lahan?->luas_ha ?? 0, 2, ',', '.') }} ha</span>
            </div>
            <div class="detail-cell" style="grid-column:1 / -1; border-right:none;">
                <span class="detail-cell-label">Status Lahan</span>
                <span class="detail-cell-value">
                    {{ \App\Models\Lahan::STATUS[$harvest->lahan?->status_lahan ?? ''] ?? ($harvest->lahan?->status_lahan ?? '-') }}
                </span>
            </div>
        </div>
    </section>
</div>

{{-- ── Blockchain record ─────────────────────────────────────────── --}}
@if ($harvest->tx_hash)
<section class="action-card" style="margin-bottom:1.5rem;">
    <h2 class="card-title">⛓️ Rekam Blockchain</h2>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div>
            <p style="margin:0 0 0.4rem; font-size:0.82rem; font-weight:700; color:var(--sips-muted);">
                Transaction Hash
            </p>
            <div class="hash-box">{{ $harvest->tx_hash }}</div>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.65rem;">
            <div>
                <p style="margin:0 0 0.2rem; font-size:0.82rem; font-weight:700; color:var(--sips-muted);">Block Number</p>
                <p style="margin:0; font-family:monospace; font-weight:700; font-size:1.1rem; color:var(--sips-primary);">
                    #{{ number_format($harvest->block_number, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p style="margin:0 0 0.2rem; font-size:0.82rem; font-weight:700; color:var(--sips-muted);">Dikonfirmasi Pada</p>
                <p style="margin:0; font-weight:700; font-size:0.9rem;">
                    {{ $harvest->blockchain_confirmed_at?->format('d M Y – H:i') ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── Footer watermark ─────────────────────────────────────────── --}}
<div style="text-align:center; padding:1.5rem 1rem; color:var(--sips-muted); font-size:0.82rem; border-top:1px solid var(--sips-border);">
    <p style="margin:0;">
        🌴 <strong>Sawit Maju Makmur Supply Chain</strong> — Data ini diverifikasi secara kriptografis di blockchain.
    </p>
    <p style="margin:0.3rem 0 0;">
        Diterbitkan pada {{ $harvest->created_at->format('d M Y H:i') }}
    </p>
</div>

@endsection
