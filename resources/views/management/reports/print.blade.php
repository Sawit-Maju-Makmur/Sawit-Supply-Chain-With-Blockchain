<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ match($report) {'suppliers'=>'Laporan Pemasok','sustainability'=>'Laporan Sustainability','traceability'=>'Laporan Traceability','expiry'=>'Laporan Kedaluwarsa'} }}</title>
    <style>
        body{font-family:'DejaVu Sans',Arial,sans-serif;color:#12261c;font-size:11px;margin:24px}
        .kop{border-bottom:3px double #12382a;padding-bottom:12px;margin-bottom:18px;text-align:center}
        .kop h1{font-size:20px;margin:2px 0}.kop p{margin:2px;color:#5d7066}
        .meta{display:flex;justify-content:space-between;margin:12px 0}.filters{background:#f3f6f4;padding:8px;border:1px solid #dfe7e2}
        table{width:100%;border-collapse:collapse;margin-top:12px}th,td{border:1px solid #dfe7e2;padding:7px;text-align:left;vertical-align:top}
        th{background:#12382a;color:#fff;font-size:9px;text-transform:uppercase}.hash{font-size:8px;word-break:break-all}
        .summary{display:flex;gap:10px;margin:12px 0}.summary div{flex:1;border:1px solid #dfe7e2;padding:8px}.summary strong{display:block;font-size:16px}
        .verify{border:1px solid #dfe7e2;margin-top:24px;padding:12px}.verify-grid{display:flex;justify-content:space-between;margin-top:30px}.verify-grid div{width:30%;border-top:1px solid #12261c;text-align:center;padding-top:5px}
        @media print{@page{size:A4 landscape;margin:12mm}.no-print{display:none}}
    </style>
</head>
<body>
    <header class="kop">
        <h1>SIPS SAWIT</h1>
        <p>Sistem Informasi Evaluasi Pemasok Berkelanjutan pada Rantai Pasok Kelapa Sawit</p>
        <p><strong>{{ match($report) {'suppliers'=>'Laporan Pemasok','sustainability'=>'Laporan Sustainability','traceability'=>'Laporan Traceability','expiry'=>'Laporan Kedaluwarsa'} }}</strong></p>
    </header>
    <div class="meta"><strong>PBI 17, Sprint 2</strong><span>Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</span></div>
    <div class="filters">
        Rentang: {{ $filters['date_from'] ?: 'Semua' }} s/d {{ $filters['date_to'] ?: 'Semua' }} |
        Kategori: {{ $filters['category'] }} | Tipe Pemasok: {{ $filters['supplier_type'] }}
    </div>
    <div class="summary">
        <div>Total Pemasok<strong>{{ $metrics['total_suppliers'] }}</strong></div>
        <div>Pemasok Hijau<strong>{{ $metrics['green_percent'] }}%</strong></div>
        <div>Batch Terlacak Penuh<strong>{{ $metrics['full_trace'] }}</strong></div>
        <div>Dokumen Hampir Kedaluwarsa<strong>{{ $metrics['expiring_documents'] }}</strong></div>
    </div>

    @if($report === 'suppliers')
        <table><thead><tr><th>Nama Pemasok</th><th>Tipe</th><th>Kabupaten</th><th>Jumlah Lahan & Luas ha</th><th>Status Akun</th><th>Kelengkapan Dokumen</th></tr></thead><tbody>
        @foreach($rows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['type'] }}</td><td>{{ $row['regency'] }}</td><td>{{ $row['lands'] }} lahan / {{ $row['area'] }} ha</td><td>{{ $row['account_status'] }}</td><td>{{ $row['documents'] }}</td></tr>@endforeach
        </tbody></table>
    @elseif($report === 'sustainability')
        <table><thead><tr><th>Nama Pemasok</th><th>Versi Kriteria</th><th>Skor</th><th>Kategori</th><th>Kelayakan</th><th>Tanggal Verifikasi</th></tr></thead><tbody>
        @foreach($rows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['version'] }}</td><td>{{ $row['score'] }}</td><td>{{ $row['category'] }}</td><td>{{ $row['eligibility'] }}</td><td>{{ $row['verified_at'] }}</td></tr>@endforeach
        </tbody></table>
    @elseif($report === 'traceability')
        <table><thead><tr><th>Kode Batch</th><th>Pemasok</th><th>Lahan/Blok & Kabupaten</th><th>Komoditas & Berat</th><th>Status Kirim/Terima</th><th>Hash SHA-256 Ledger</th><th>Integritas</th></tr></thead><tbody>
        @foreach($rows as $row)<tr><td>{{ $row['batch'] }}</td><td>{{ $row['supplier'] }}</td><td>{{ $row['land'] }} / {{ $row['regency'] }}</td><td>{{ $row['commodity'] }} / {{ number_format($row['weight']) }} kg</td><td>{{ $row['shipment_status'] }} / {{ $row['mill_status'] }}</td><td class="hash">{{ $row['hash'] }}</td><td>{{ $row['integrity'] }}</td></tr>@endforeach
        </tbody></table>
    @else
        <table><thead><tr><th>Nama Pemasok</th><th>Jenis & Nomor Dokumen</th><th>Lahan</th><th>Tanggal Kedaluwarsa</th><th>Sisa Hari</th><th>Status</th></tr></thead><tbody>
        @foreach($rows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['document_type'] }} / {{ $row['document_number'] }}</td><td>{{ $row['land'] }}</td><td>{{ $row['expiry_date'] }}</td><td>{{ $row['remaining_days'] }}</td><td>{{ $row['status'] }}</td></tr>@endforeach
        </tbody></table>
    @endif

    @if($report === 'traceability')
        <div class="verify"><strong>Blok Verifikasi Hash Ledger</strong><p>Seluruh hash di atas wajib dipertahankan sesuai bukti ledger dan diverifikasi sebagai integritas rantai Valid/Rusak.</p></div>
    @endif
    <div class="verify-grid"><div>Disiapkan oleh</div><div>Diperiksa oleh</div><div>Disahkan oleh</div></div>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
