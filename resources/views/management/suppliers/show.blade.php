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

    <div class="card">
        <h3>Lahan ({{ $supplier->plots->count() }})</h3>
        <div class="tw">
            <table>
                <thead><tr><th>Nama lahan</th><th>Kabupaten</th><th>Luas (ha)</th><th>Kepemilikan</th><th>Tahun tanam</th></tr></thead>
                <tbody>
                @forelse ($supplier->plots as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->regency ?? '-' }}</td>
                        <td>{{ $p->area_ha }}</td>
                        <td>{{ \App\Models\Plot::OWNERSHIP[$p->ownership_status] ?? $p->ownership_status }}</td>
                        <td>{{ $p->planting_year ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="mut">Belum ada lahan terdaftar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
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

