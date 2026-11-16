@extends('layouts.management')

@section('title', $supplier->name)

@section('content')
    <p><a href="{{ route('management.suppliers.index') }}" class="mut">&larr; Kembali ke daftar pemasok</a></p>

    <div class="hd">
        <div>
            <h1 dusk="supplier-name">{{ $supplier->name }}</h1>
            <p class="mut">
                {{ $supplier->type_label }}
                <span class="chip {{ $supplier->status === 'active' ? 'ok' : ($supplier->status === 'inactive' ? 'er' : 'wa') }}" dusk="supplier-status">{{ $supplier->status_label }}</span>
                <span class="chip {{ $hasValidCertification ? 'ok' : 'er' }}" dusk="supplier-certification">
                    {{ $hasValidCertification ? 'Sertifikasi valid' : 'Tidak layak beroperasi' }}
                </span>
            </p>
        </div>
        <span class="pbi">PBI 9</span>
    </div>

    <div class="grid2">
        <div class="card">
            <h3>Data pemasok</h3>
            <dl>
                <dt>NIK</dt><dd>{{ $supplier->nik ?? '-' }}</dd>
                <dt>NPWP</dt><dd>{{ $supplier->npwp ?? '-' }}</dd>
                <dt>NIB</dt><dd>{{ $supplier->nib ?? '-' }}</dd>
                <dt>Alamat</dt><dd>{{ $supplier->address ?? '-' }}</dd>
                <dt>Kabupaten</dt><dd>{{ $supplier->regency ?? '-' }}</dd>
                <dt>Telepon</dt><dd>{{ $supplier->phone ?? '-' }}</dd>
                <dt>Email</dt><dd>{{ $supplier->email ?? '-' }}</dd>
                <dt>Terdaftar</dt><dd>{{ optional($supplier->created_at)->format('d/m/Y') }}</dd>
            </dl>
        </div>

        <div class="card">
            <h3>Ringkasan status dokumen</h3>
            <p>
                @foreach (\App\Models\Document::STATUSES as $key => $label)
                    <span class="chip {{ $key === 'approved' ? 'ok' : ($key === 'pending' ? 'wa' : 'er') }}" dusk="doc-{{ $key }}">{{ $label }} {{ $documentSummary[$key] ?? 0 }}</span>
                @endforeach
            </p>
            @if ($supplier->status === 'inactive' && $supplier->deactivation_reason)
                <p class="mut">Alasan penonaktifan: {{ $supplier->deactivation_reason }}</p>
            @endif
        </div>
    </div>

    @php
        $lahanItems = $supplier->lahans->isNotEmpty() ? $supplier->lahans : $supplier->plots;
    @endphp
    <div class="card">
        <h3>Lahan ({{ $lahanItems->count() }})</h3>
        <div class="tw">
            <table>
                <thead><tr><th>Nama kebun / lahan</th><th>Lokasi / Kabupaten</th><th>Luas (ha)</th><th>Status lahan</th><th>Batas poligon</th></tr></thead>
                <tbody>
                @forelse ($lahanItems as $p)
                    <tr>
                        <td>{{ $p->nama_kebun ?? $p->name }}</td>
                        <td>{{ $p->lokasi ?? $p->regency ?? '-' }}</td>
                        <td>{{ $p->luas_ha ?? $p->area_ha }}</td>
                        <td>{{ \App\Models\Lahan::STATUS[$p->status_lahan ?? ''] ?? \App\Models\Plot::OWNERSHIP[$p->ownership_status ?? ''] ?? ($p->status_lahan ?? $p->ownership_status ?? '-') }}</td>
                        <td>
                            @if (! empty($p->polygon_coordinates) && count($p->polygon_coordinates) >= 3)
                                <span class="chip ok">Ada ({{ count($p->polygon_coordinates) }} titik)</span>
                            @else
                                <span class="mut">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="mut">Belum ada lahan terdaftar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top: 1rem;">
        <h3>Evaluasi Keberlanjutan Mandiri (Self-Assessment)</h3>
        @if ($supplier->latestSelfAssessment)
            @php $assessment = $supplier->latestSelfAssessment; $info = $assessment->level_info; @endphp
            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                <div>
                    <span class="chip {{ $assessment->level === 'Tinggi' ? 'ok' : ($assessment->level === 'Sedang' ? 'wa' : 'er') }}" style="font-size: 0.95rem; font-weight: 700; padding: 4px 12px;" dusk="assessment-level">
                        Level: {{ $assessment->level }}
                    </span>
                </div>
                <div>
                    <strong>{{ rtrim(rtrim(number_format($assessment->percentage, 2), '0'), '.') }}%</strong>
                    <span class="mut">({{ $assessment->total_score }}/{{ $assessment->max_score }} poin)</span>
                </div>
                <div class="mut">
                    Diisi: {{ $assessment->created_at->translatedFormat('d F Y, H:i') }} (Kebun: {{ $assessment->nama_kebun }})
                </div>
            </div>
            @if ($assessment->catatan)
                <p class="mut" style="margin-top: 0.5rem;">Catatan: {{ $assessment->catatan }}</p>
            @endif
        @else
            <p class="mut" dusk="assessment-empty">Pemasok belum pernah mengisi formulir self-assessment keberlanjutan.</p>
        @endif
    </div>

    <div class="grid2">
        <div class="card">
            <h3>Status pemasok</h3>
            @if ($canManage)
                @if ($supplier->status !== 'inactive')
                    <form method="POST" action="{{ route('management.suppliers.status', $supplier) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="inactive">
                        <label for="reason">Alasan penonaktifan (wajib)</label>
                        <textarea id="reason" name="reason" dusk="reason">{{ old('reason') }}</textarea>
                        @error('reason')<p class="err" dusk="error-reason">{{ $message }}</p>@enderror
                        <p><button type="submit" class="btn d" dusk="btn-deactivate">Nonaktifkan pemasok</button></p>
                    </form>
                @endif
                @if ($supplier->status !== 'active')
                    <form method="POST" action="{{ route('management.suppliers.status', $supplier) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <p><button type="submit" class="btn" dusk="btn-activate">Aktifkan pemasok</button></p>
                    </form>
                @endif
            @else
                <p class="mut">Anda hanya dapat melihat data ini. Perubahan status dilakukan oleh Management.</p>
            @endif
        </div>

        <div class="card">
            <h3>Riwayat status</h3>
            @forelse ($supplier->statusLogs as $log)
                <p dusk="log-{{ $log->id }}">
                    <strong>{{ \App\Models\Supplier::STATUSES[$log->from_status] ?? $log->from_status }} menjadi {{ \App\Models\Supplier::STATUSES[$log->to_status] ?? $log->to_status }}</strong><br>
                    <span class="mut">{{ $log->created_at->format('d/m/Y H:i') }}, oleh {{ $log->actor->name ?? 'sistem' }}@if ($log->reason), alasan: {{ $log->reason }}@endif</span>
                </p>
            @empty
                <p class="mut">Belum ada perubahan status.</p>
            @endforelse
        </div>
    </div>
@endsection

