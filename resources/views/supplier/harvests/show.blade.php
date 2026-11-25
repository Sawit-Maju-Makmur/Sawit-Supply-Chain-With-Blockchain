@extends('layouts.app')

@section('title', 'Detail Panen – ' . $harvest->kode_panen)

@push('styles')
<style>
    .qr-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 1.5rem;
        background: #fff;
        border: 2px solid var(--sips-border);
        border-radius: 1.25rem;
        box-shadow: 0 8px 25px rgba(10,42,31,0.07);
    }

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

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--sips-border);
        font-size: 0.9rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--sips-muted); font-weight: 600; white-space: nowrap; }
    .detail-value { font-weight: 700; text-align: right; color: var(--sips-text); }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">🌿 Hasil Panen</p>
        <h1 class="dashboard-title" style="font-size:1.8rem;">
            {{ $harvest->kode_panen }}
        </h1>
    </div>
    <a href="{{ route('pemasok.harvests.index') }}"
       style="color:var(--sips-muted); font-size:0.9rem;">← Kembali</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.5rem; align-items:start;">

    {{-- ── Kolom kiri: Detail panen + info blockchain ──────────────────── --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Detail Panen --}}
        <section class="action-card">
            <h2 class="card-title">📋 Detail Muatan Panen</h2>
            <div>
                <div class="detail-row">
                    <span class="detail-label">Kode Panen</span>
                    <span class="detail-value" style="font-family:monospace; color:var(--sips-primary);">
                        {{ $harvest->kode_panen }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jenis Komoditas</span>
                    <span class="detail-value">{{ $harvest->jenis_komoditas }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jumlah</span>
                    <span class="detail-value">{{ number_format($harvest->jumlah_kg, 2, ',', '.') }} kg</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Panen</span>
                    <span class="detail-value">{{ $harvest->tanggal_panen->translatedFormat('d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lahan / Kebun</span>
                    <span class="detail-value">{{ $harvest->lahan?->nama_kebun ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lokasi Lahan</span>
                    <span class="detail-value">{{ $harvest->lahan?->lokasi ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Luas Lahan</span>
                    <span class="detail-value">{{ number_format($harvest->lahan?->luas_ha ?? 0, 2, ',', '.') }} ha</span>
                </div>
                @if ($harvest->keterangan)
                <div class="detail-row">
                    <span class="detail-label">Keterangan</span>
                    <span class="detail-value" style="text-align:left; max-width:60%;">{{ $harvest->keterangan }}</span>
                </div>
                @endif
            </div>
        </section>

        {{-- Informasi Pemasok --}}
        <section class="action-card">
            <h2 class="card-title">🏭 Informasi Pemasok</h2>
            <div>
                <div class="detail-row">
                    <span class="detail-label">Nama Pemasok</span>
                    <span class="detail-value">{{ $harvest->supplier?->name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipe</span>
                    <span class="detail-value">{{ $harvest->supplier?->type_label ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">NIK</span>
                    <span class="detail-value" style="font-family:monospace;">
                        {{ $harvest->supplier?->nik ?? '-' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">NPWP</span>
                    <span class="detail-value" style="font-family:monospace;">
                        {{ $harvest->supplier?->npwp ?? '-' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alamat</span>
                    <span class="detail-value" style="text-align:right; max-width:60%;">
                        {{ $harvest->supplier?->address ?? '-' }}
                    </span>
                </div>
            </div>
        </section>

        {{-- Blockchain Info --}}
        <section class="action-card">
            <h2 class="card-title">⛓️ Rekam Blockchain</h2>
            @php
                $bc = $harvest->blockchain_status;
                $colors = [
                    'confirmed' => ['bg'=>'#dcfce7','color'=>'#166534','icon'=>'✅'],
                    'pending'   => ['bg'=>'#fef9c3','color'=>'#92400e','icon'=>'⏳'],
                    'failed'    => ['bg'=>'#fee2e2','color'=>'#991b1b','icon'=>'❌'],
                ];
                $c = $colors[$bc] ?? ['bg'=>'#f1f5f9','color'=>'#475569','icon'=>'❓'];
            @endphp
            <div style="display:inline-flex; align-items:center; gap:0.5rem;
                        padding:0.5rem 1rem; border-radius:999px; margin-bottom:1rem;
                        background:{{ $c['bg'] }}; color:{{ $c['color'] }};
                        font-weight:700; font-size:0.88rem;">
                {{ $c['icon'] }} {{ $harvest->blockchain_status_label }}
            </div>

            @if ($harvest->tx_hash)
            <div>
                <p style="margin:0 0 0.35rem; font-size:0.82rem; font-weight:700; color:var(--sips-muted);">
                    Transaction Hash
                </p>
                <div class="hash-box">{{ $harvest->tx_hash }}</div>
            </div>
            <div class="detail-row" style="margin-top:0.75rem;">
                <span class="detail-label">Block Number</span>
                <span class="detail-value" style="font-family:monospace;">
                    #{{ number_format($harvest->block_number, 0, ',', '.') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Dikonfirmasi pada</span>
                <span class="detail-value">
                    {{ $harvest->blockchain_confirmed_at?->format('d M Y H:i') ?? '-' }}
                </span>
            </div>
            @endif
        </section>
    </div>

    {{-- ── Kolom kanan: QR Code ───────────────────────────────────────── --}}
    <div>
        <section class="action-card">
            <h2 class="card-title" style="text-align:center;">📱 QR Code Verifikasi</h2>

            <div class="qr-wrapper" id="qr-section">
                <div id="qrcode"></div>
                <p style="margin:0; font-size:0.78rem; color:var(--sips-muted); text-align:center; max-width:220px; line-height:1.5;">
                    Pindai QR Code ini untuk memverifikasi asal-usul hasil panen di blockchain.
                </p>
            </div>

            <div style="margin-top:1rem;">
                <p style="margin:0 0 0.4rem; font-size:0.78rem; font-weight:700; color:var(--sips-muted);">
                    URL Verifikasi
                </p>
                <div class="hash-box" id="verify-url">{{ $harvest->qr_url }}</div>
            </div>

            <div style="margin-top:1rem; display:flex; flex-direction:column; gap:0.65rem;">
                <button onclick="copyVerifyUrl()" class="pill-link"
                        style="border:0; cursor:pointer; width:100%; justify-content:center; font-size:0.85rem; padding:0.65rem;">
                    📋 Salin URL Verifikasi
                </button>
                <button onclick="printQr()" class="pill-link"
                        style="border:0; cursor:pointer; width:100%; justify-content:center;
                               background:#0ea5e9; font-size:0.85rem; padding:0.65rem;">
                    🖨️ Cetak QR Code
                </button>
            </div>

            <p style="margin:1rem 0 0; font-size:0.75rem; color:var(--sips-muted); text-align:center; line-height:1.5;">
                Token: <span style="font-family:monospace;">{{ $harvest->qr_token }}</span>
            </p>
        </section>
    </div>

</div>
@endsection

@push('scripts')
{{-- QRCode.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
@php
    $qrPrintData = [
        'kode' => $harvest->kode_panen,
        'nama' => $harvest->supplier?->name ?? '-',
        'kebun' => $harvest->lahan?->nama_kebun ?? '-',
        'kg' => number_format($harvest->jumlah_kg, 2, ',', '.'),
        'tgl' => $harvest->tanggal_panen?->format('d M Y') ?? '-',
    ];
@endphp
<script>
    const verifyUrl = @json($harvest->qr_url);

    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text       : verifyUrl,
        width      : 200,
        height     : 200,
        colorDark  : "#00603a",
        colorLight : "#ffffff",
        correctLevel: QRCode.CorrectLevel.H,
    });

    function copyVerifyUrl() {
        navigator.clipboard.writeText(verifyUrl).then(() => {
            alert('URL verifikasi berhasil disalin!');
        }).catch(() => {
            const el = document.getElementById('verify-url');
            const range = document.createRange();
            range.selectNodeContents(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            alert('URL verifikasi berhasil disalin!');
        });
    }

    function printQr() {
        const qrCanvas = document.querySelector('#qrcode canvas') || document.querySelector('#qrcode img');
        const printWindow = window.open('', '_blank', 'width=600,height=700');
        const printData = @json($qrPrintData);
        const kode = printData.kode;
        const nama = printData.nama;
        const kebun = printData.kebun;
        const kg = printData.kg;
        const tgl = printData.tgl;

        let imgSrc = '';
        if (qrCanvas && qrCanvas.tagName === 'CANVAS') {
            imgSrc = qrCanvas.toDataURL('image/png');
        } else if (qrCanvas && qrCanvas.tagName === 'IMG') {
            imgSrc = qrCanvas.src;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>QR Code – ${kode}</title>
                <style>
                    body { font-family: Arial, sans-serif; display:flex; flex-direction:column;
                           align-items:center; justify-content:center; min-height:100vh;
                           margin:0; padding:1.5rem; background:#fff; color:#123427; }
                    .card { border:2px solid #00603a; border-radius:1rem; padding:1.5rem;
                            text-align:center; max-width:340px; }
                    h2 { color:#00603a; margin:0 0 0.5rem; font-size:1rem; }
                    img { margin:1rem 0; }
                    table { width:100%; font-size:0.82rem; border-collapse:collapse; margin-top:0.75rem; text-align:left; }
                    td { padding:0.3rem 0.4rem; border-bottom:1px solid #dfeae3; }
                    td:first-child { font-weight:700; color:#5a6f65; width:50%; }
                    .footer { margin-top:0.75rem; font-size:0.72rem; color:#5a6f65; word-break:break-all; }
                </style>
            </head>
            <body>
                <div class="card">
                    <h2>🌴 Sawit Maju Makmur</h2>
                    <p style="margin:0; font-size:0.78rem; color:#5a6f65;">QR Code Verifikasi Hasil Panen</p>
                    <img src="${imgSrc}" width="200" height="200" alt="QR Code"/>
                    <table>
                        <tr><td>Kode Panen</td><td><strong>${kode}</strong></td></tr>
                        <tr><td>Pemasok</td><td>${nama}</td></tr>
                        <tr><td>Lahan / Kebun</td><td>${kebun}</td></tr>
                        <tr><td>Jumlah</td><td>${kg} kg</td></tr>
                        <tr><td>Tanggal Panen</td><td>${tgl}</td></tr>
                    </table>
                    <p class="footer">Pindai QR Code di atas atau kunjungi:<br><strong>${verifyUrl}</strong></p>
                </div>
                <script>window.onload = () => window.print();<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush
