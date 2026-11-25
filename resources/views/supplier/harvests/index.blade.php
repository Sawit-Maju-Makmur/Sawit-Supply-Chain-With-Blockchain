@extends('layouts.app')

@section('title', 'Riwayat Hasil Panen')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">🌿 Hasil Panen</p>
        <h1 class="dashboard-title">Riwayat Panen Saya</h1>
    </div>
    <a href="{{ route('pemasok.harvests.create') }}" class="pill-link" style="font-size:0.9rem; padding:0.6rem 1.2rem;">
        + Catat Panen Baru
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($harvests->isEmpty())
    <div style="text-align:center; padding:3rem 1rem; color:var(--sips-muted);">
        <div style="font-size:3.5rem; margin-bottom:1rem;">🌾</div>
        <p style="font-size:1.1rem; font-weight:600; margin:0 0 0.5rem;">Belum ada data panen.</p>
        <p style="margin:0 0 1.5rem; font-size:0.9rem;">Catat hasil panen pertama Anda dan terbitkan QR Code blockchain.</p>
        <a href="{{ route('pemasok.harvests.create') }}" class="pill-link">Catat Panen Pertama</a>
    </div>
@else
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.88rem;">
            <thead>
                <tr style="background:var(--sips-primary-soft); color:var(--sips-primary);">
                    <th style="padding:0.75rem 1rem; text-align:left; border-radius:0.5rem 0 0 0;">Kode Panen</th>
                    <th style="padding:0.75rem 1rem; text-align:left;">Lahan / Kebun</th>
                    <th style="padding:0.75rem 1rem; text-align:left;">Komoditas</th>
                    <th style="padding:0.75rem 1rem; text-align:right;">Jumlah (kg)</th>
                    <th style="padding:0.75rem 1rem; text-align:center;">Tanggal Panen</th>
                    <th style="padding:0.75rem 1rem; text-align:center;">Blockchain</th>
                    <th style="padding:0.75rem 1rem; text-align:center; border-radius:0 0.5rem 0.5rem 0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($harvests as $harvest)
                    <tr style="border-bottom:1px solid var(--sips-border);">
                        <td style="padding:0.75rem 1rem;">
                            <span style="font-weight:700; font-family:monospace; color:var(--sips-primary);">
                                {{ $harvest->kode_panen }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem;">
                            {{ $harvest->lahan?->nama_kebun ?? '-' }}
                            <span style="display:block; font-size:0.78rem; color:var(--sips-muted);">
                                {{ $harvest->lahan?->lokasi ?? '' }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem;">{{ $harvest->jenis_komoditas }}</td>
                        <td style="padding:0.75rem 1rem; text-align:right; font-weight:700;">
                            {{ number_format($harvest->jumlah_kg, 2, ',', '.') }}
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center;">
                            {{ $harvest->tanggal_panen->format('d M Y') }}
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center;">
                            @php
                                $bc = $harvest->blockchain_status;
                                $colors = [
                                    'confirmed' => ['bg'=>'#dcfce7','color'=>'#166534'],
                                    'pending'   => ['bg'=>'#fef9c3','color'=>'#92400e'],
                                    'failed'    => ['bg'=>'#fee2e2','color'=>'#991b1b'],
                                ];
                                $c = $colors[$bc] ?? ['bg'=>'#f1f5f9','color'=>'#475569'];
                            @endphp
                            <span style="
                                display:inline-block;
                                padding:0.25rem 0.65rem;
                                border-radius:999px;
                                font-size:0.75rem;
                                font-weight:700;
                                background:{{ $c['bg'] }};
                                color:{{ $c['color'] }};
                            ">
                                {{ $harvest->blockchain_status_label }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem; text-align:center;">
                            <a href="{{ route('pemasok.harvests.show', $harvest) }}"
                               class="pill-link"
                               style="font-size:0.78rem; padding:0.4rem 0.8rem;">
                                Detail & QR
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $harvests->links() }}
    </div>
@endif
@endsection
