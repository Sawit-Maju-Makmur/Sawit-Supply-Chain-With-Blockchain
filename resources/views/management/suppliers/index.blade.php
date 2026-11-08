@extends('layouts.management')

@section('title', 'Data pemasok')

@section('content')
    <div class="hd">
        <div>
            <h1>Data pemasok</h1>
            <p class="mut">Kelola seluruh pemasok: cari, filter, lihat detail, dan atur status.</p>
        </div>
        <span class="pbi">PBI 9</span>
    </div>

    <form method="GET" action="{{ route('management.suppliers.index') }}" class="bar">
        <input type="text" name="q" dusk="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama pemasok">
        <select name="type" dusk="type">
            <option value="">Semua tipe</option>
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" dusk="status">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn" dusk="filter-submit">Cari</button>
        <a class="btn l" href="{{ route('management.suppliers.index') }}" dusk="reset">Reset</a>
        @if ($canManage)
            <a class="btn l" dusk="export" href="{{ route('management.suppliers.export', $filters) }}">Export Excel</a>
        @endif
    </form>

    <div class="card">
        <div class="tw">
            <table>
                <thead>
                <tr>
                    <th>Pemasok</th><th>Tipe</th><th>Kabupaten</th><th>Jumlah lahan</th><th>Status</th><th>Tanggal daftar</th><th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($suppliers as $s)
                    <tr dusk="row-{{ $s->id }}">
                        <td><strong>{{ $s->name }}</strong></td>
                        <td>{{ $s->type_label }}</td>
                        <td>{{ $s->regency ?? '-' }}</td>
                        <td>{{ $s->plots_count }}</td>
                        <td>
                            <span class="chip {{ $s->status === 'active' ? 'ok' : ($s->status === 'inactive' ? 'er' : 'wa') }}">{{ $s->status_label }}</span>
                        </td>
                        <td>{{ optional($s->created_at)->format('d/m/Y') }}</td>
                        <td><a class="btn l" dusk="detail-{{ $s->id }}" href="{{ route('management.suppliers.show', $s) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="mut" dusk="empty">Tidak ada pemasok yang cocok dengan pencarian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pg">
            <span class="mut">{{ $suppliers->total() }} pemasok, halaman {{ $suppliers->currentPage() }} dari {{ $suppliers->lastPage() }}</span>
            <span>
                @if ($suppliers->previousPageUrl())<a class="btn l" dusk="prev" href="{{ $suppliers->previousPageUrl() }}">Sebelumnya</a>@endif
                @if ($suppliers->nextPageUrl())<a class="btn l" dusk="next" href="{{ $suppliers->nextPageUrl() }}">Berikutnya</a>@endif
            </span>
        </div>
    </div>
@endsection

