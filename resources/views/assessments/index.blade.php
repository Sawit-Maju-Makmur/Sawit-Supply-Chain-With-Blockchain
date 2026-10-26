@extends('layouts.app')

@section('title', 'Riwayat Self-Assessment')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 class="page-title">Riwayat Self-Assessment</h1>
            <p class="page-sub" style="margin-bottom:0;">Daftar formulir mandiri sustainability &amp; traceability yang telah diisi.</p>
        </div>
        <a href="{{ route('assessments.create') }}" class="btn btn-primary">+ Isi Formulir</a>
    </div>

    <div class="card" style="margin-top:20px;">
        @if ($assessments->isEmpty())
            <div class="empty">
                Belum ada data. <a href="{{ route('assessments.create') }}" style="color:var(--green-600);font-weight:600;">Isi formulir pertama Anda</a>.
            </div>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Petani</th>
                            <th>Kebun</th>
                            <th>Luas (Ha)</th>
                            <th>Skor</th>
                            <th>Kepatuhan</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assessments as $a)
                            <tr>
                                <td>{{ $assessments->firstItem() + $loop->index }}</td>
                                <td>{{ $a->nama_petani }}</td>
                                <td>{{ $a->nama_kebun }}</td>
                                <td>{{ $a->luas_lahan }}</td>
                                <td>{{ rtrim(rtrim(number_format($a->percentage, 2), '0'), '.') }}%</td>
                                <td><span class="badge {{ $a->level_info['class'] }}">{{ $a->level }}</span></td>
                                <td>{{ $a->created_at->format('d/m/Y') }}</td>
                                <td style="white-space:nowrap;">
                                    <a href="{{ route('assessments.show', $a) }}" class="btn btn-outline btn-sm">Detail</a>
                                    <form action="{{ route('assessments.destroy', $a) }}" method="POST" style="display:inline"
                                          onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($assessments->hasPages())
                <div class="pagination" style="align-items:center;">
                    @if ($assessments->previousPageUrl())
                        <a href="{{ $assessments->previousPageUrl() }}" class="btn btn-outline btn-sm">&laquo; Sebelumnya</a>
                    @endif
                    <span style="color:var(--muted);font-size:.9rem;">
                        Halaman {{ $assessments->currentPage() }} dari {{ $assessments->lastPage() }}
                    </span>
                    @if ($assessments->nextPageUrl())
                        <a href="{{ $assessments->nextPageUrl() }}" class="btn btn-outline btn-sm">Berikutnya &raquo;</a>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
