@extends('layouts.app')

@section('content')
<div class="shell audit-shell">
    <aside class="sidebar">
        <div class="brand"><span class="brand-mark">S</span><span>SIPS Sawit</span></div>
        <div class="side-label">Management</div>
        <nav>
            <a class="nav-link" href="{{ route('assessment-criteria.index') }}"><span>⚙</span> Kriteria penilaian</a>
            <a class="nav-link active" href="{{ route('management.reports.index') }}"><span>▤</span> Laporan audit</a>
        </nav>
    </aside>

    <main class="main audit-main">
        <header class="hd">
            <div>
                <h1>Laporan audit</h1>
                <p>Buat laporan pemasok dan sustainability yang siap dipakai untuk audit.</p>
            </div>
            <span class="pbi">PBI 17, Sprint 2</span>
        </header>

        <section class="g4">
            <article class="stat"><span>Total Pemasok</span><strong>{{ $metrics['total_suppliers'] }}</strong></article>
            <article class="stat"><span>Pemasok Hijau</span><strong>{{ $metrics['green_percent'] }}%</strong></article>
            <article class="stat"><span>Batch Terlacak Penuh</span><strong>{{ $metrics['full_trace'] }}</strong></article>
            <article class="stat"><span>Dokumen Hampir Kedaluwarsa</span><strong>{{ $metrics['expiring_documents'] }}</strong></article>
        </section>

        <form method="GET" action="{{ route('management.reports.index') }}" class="filters card">
            <div><label for="date_from">Dari tanggal</label><input id="date_from" type="date" name="date_from" value="{{ $filters['date_from'] }}"></div>
            <div><label for="date_to">Sampai tanggal</label><input id="date_to" type="date" name="date_to" value="{{ $filters['date_to'] }}"></div>
            <div><label for="category">Kategori Skor</label>
                <select id="category" name="category">
                    @foreach(['Semua','Hijau','Kuning','Merah'] as $option)<option @selected($filters['category'] === $option)>{{ $option }}</option>@endforeach
                </select>
            </div>
            <div><label for="supplier_type">Tipe Pemasok</label>
                <select id="supplier_type" name="supplier_type">
                    @foreach(['Semua','Mandiri','Perusahaan','Plasma'] as $option)<option @selected($filters['supplier_type'] === $option)>{{ $option }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Terapkan Filter</button>
        </form>

        @php
            $cards = [
                'suppliers' => ['Laporan pemasok', 'Daftar pemasok, lahan, dan status dokumen'],
                'sustainability' => ['Laporan sustainability', 'Skor, kategori, dan riwayat verifikasi'],
                'traceability' => ['Laporan traceability', 'Rantai asal TBS dan bukti hash ledger'],
                'expiry' => ['Laporan kedaluwarsa', 'Dokumen yang akan berakhir 90 hari ke depan'],
            ];
        @endphp

        <section class="row2">
            @foreach($cards as $key => $card)
                <article class="card report-card">
                    <div class="card-head">
                        <div><h2>{{ $card[0] }}</h2><p>{{ $card[1] }}</p></div>
                        @if($key === 'sustainability')
                            <span class="chip ok">{{ count($reportData[$key]) }} record</span>
                        @endif
                    </div>
                    <div class="report-actions">
                        <a class="btn s" href="{{ route('management.reports.preview', array_merge(['report'=>$key], $filters)) }}">Pratinjau</a>
                        <a class="btn s" href="{{ route('management.reports.pdf', array_merge(['report'=>$key], $filters)) }}">Buat PDF</a>
                        <a class="btn s l" href="{{ route('management.reports.excel', array_merge(['report'=>$key], $filters)) }}">Buat Excel</a>
                    </div>
                </article>
            @endforeach
        </section>

        @if($preview)
            <section class="card preview-card" id="preview">
                <div class="card-head">
                    <div><h2>Pratinjau {{ $cards[$preview][0] }}</h2><p>Hasil berdasarkan filter laporan aktif.</p></div>
                    <span class="chip ok">{{ count($previewRows) }} data</span>
                </div>
                <div class="wrap">
                    @if($preview === 'suppliers')
                        <table><thead><tr><th>Nama Pemasok</th><th>Tipe</th><th>Kabupaten</th><th>Jumlah Lahan & Luas ha</th><th>Status Akun</th><th>Kelengkapan Dokumen</th></tr></thead><tbody>
                        @forelse($previewRows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['type'] }}</td><td>{{ $row['regency'] }}</td><td>{{ $row['lands'] }} lahan / {{ $row['area'] }} ha</td><td><span class="chip {{ $row['account_status']==='Aktif'?'ok':'er' }}">{{ $row['account_status'] }}</span></td><td>{{ $row['documents'] }}</td></tr>@empty<tr><td colspan="6">Tidak ada data.</td></tr>@endforelse
                        </tbody></table>
                    @elseif($preview === 'sustainability')
                        <table><thead><tr><th>Nama Pemasok</th><th>Versi Kriteria</th><th>Skor</th><th>Kategori</th><th>Kelayakan Beroperasi</th><th>Tanggal Verifikasi</th></tr></thead><tbody>
                        @forelse($previewRows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['version'] }}</td><td>{{ $row['score'] }}</td><td><span class="chip {{ $row['category']==='Hijau'?'ok':($row['category']==='Kuning'?'wa':'er') }}">{{ $row['category'] }}</span></td><td>{{ $row['eligibility'] }}</td><td>{{ $row['verified_at'] }}</td></tr>@empty<tr><td colspan="6">Tidak ada data.</td></tr>@endforelse
                        </tbody></table>
                    @elseif($preview === 'traceability')
                        <table><thead><tr><th>Kode Batch</th><th>Pemasok</th><th>Lahan/Blok & Kabupaten</th><th>Komoditas & Berat kg</th><th>Status Pengiriman & Penerimaan Mill</th><th>Hash SHA-256 Ledger</th><th>Integritas Rantai</th></tr></thead><tbody>
                        @forelse($previewRows as $row)<tr><td>{{ $row['batch'] }}</td><td>{{ $row['supplier'] }}</td><td>{{ $row['land'] }}<br>{{ $row['regency'] }}</td><td>{{ $row['commodity'] }} / {{ number_format($row['weight']) }} kg</td><td>{{ $row['shipment_status'] }} / {{ $row['mill_status'] }}</td><td class="hash">{{ $row['hash'] }}</td><td><span class="chip {{ $row['integrity']==='Valid'?'ok':'er' }}">{{ $row['integrity'] }}</span></td></tr>@empty<tr><td colspan="7">Tidak ada data.</td></tr>@endforelse
                        </tbody></table>
                    @else
                        <table><thead><tr><th>Nama Pemasok</th><th>Jenis & Nomor Dokumen</th><th>Lahan</th><th>Tanggal Kedaluwarsa</th><th>Sisa Hari</th><th>Status</th></tr></thead><tbody>
                        @forelse($previewRows as $row)<tr><td>{{ $row['name'] }}</td><td>{{ $row['document_type'] }} / {{ $row['document_number'] }}</td><td>{{ $row['land'] }}</td><td>{{ $row['expiry_date'] }}</td><td>{{ $row['remaining_days'] }}</td><td><span class="chip {{ $row['status']==='Kedaluwarsa'?'er':'wa' }}">{{ $row['status'] }}</span></td></tr>@empty<tr><td colspan="6">Tidak ada data.</td></tr>@endforelse
                        </tbody></table>
                    @endif
                </div>
            </section>
        @endif
    </main>
</div>
@endsection
