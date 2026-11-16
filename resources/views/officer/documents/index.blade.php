@extends('layouts.management')

@section('title', 'Review Dokumen Legalitas')

@section('content')
    <div class="hd">
        <div>
            <h1>Review Dokumen Legalitas</h1>
            <p class="mut">Setujui dokumen yang masih valid atau tolak dengan alasan. Pemasok aktif akan dinonaktifkan bila dokumen legalitasnya ditolak.</p>
        </div>
        <span class="pbi">Sustainability Officer</span>
    </div>

    @if ($errors->any())
        <div class="alert er" role="alert">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Pemasok / Plot</th>
                        <th>Jenis dokumen</th>
                        <th>Kedaluwarsa</th>
                        <th>Status review</th>
                        <th>Catatan</th>
                        <th>Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        @php
                            $isExpired = $document->expires_at?->lt(today()) ?? false;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $document->plot?->supplier?->name ?? 'Pemasok tidak tersedia' }}</strong><br>
                                <span class="mut">{{ $document->plot?->name ?? 'Plot tidak tersedia' }}</span>
                            </td>
                            <td>
                                {{ $document->type }}
                                @if ($document->file_path)
                                    <div><a class="mut" href="{{ route('officer.documents.download', $document) }}">Lihat berkas legalitas</a></div>
                                @else
                                    <div class="mut">Berkas belum tersedia</div>
                                @endif
                            </td>
                            <td>
                                {{ $document->expires_at?->format('d/m/Y') ?? 'Tidak dicantumkan' }}
                                @if (! $document->file_path)
                                    <p class="mut">Berkas belum diunggah; dokumen tidak dapat disetujui.</p>
                                @elseif ($isExpired)
                                    <span class="chip er">Kedaluwarsa</span>
                                @endif
                            </td>
                            <td>
                                <span class="chip {{ $document->status === 'approved' ? 'ok' : ($document->status === 'rejected' ? 'er' : 'wa') }}">
                                    {{ \App\Models\Document::STATUSES[$document->status] ?? ucfirst($document->status) }}
                                </span>
                                @if ($document->reviewed_at)
                                    <div class="mut" style="margin-top:4px;">{{ $document->reviewed_at->format('d/m/Y H:i') }} · {{ $document->reviewedBy?->name ?? 'Officer tidak tersedia' }}</div>
                                @endif
                            </td>
                            <td>{{ $document->review_notes ?: '-' }}</td>
                            <td style="min-width:260px;">
                                @if ($document->status === 'pending')
                                    @if ($isExpired)
                                        <p class="mut">Dokumen kedaluwarsa tidak dapat disetujui. Tolak dokumen dan minta pemasok mengirim dokumen valid.</p>
                                    @else
                                        <form method="POST" action="{{ route('officer.documents.review', $document) }}" style="margin-bottom:8px;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn" dusk="approve-{{ $document->id }}">Setujui</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('officer.documents.review', $document) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <textarea name="review_notes" required maxlength="2000" placeholder="Alasan penolakan" style="min-height:60px;">{{ old('review_notes') }}</textarea>
                                        <button type="submit" class="btn d" dusk="reject-{{ $document->id }}">Tolak dokumen</button>
                                    </form>
                                @else
                                    <span class="mut">Keputusan tersimpan. Ajukan dokumen baru untuk peninjauan ulang.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="mut">Belum ada dokumen legalitas yang diajukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pg">
            <span class="mut">{{ $documents->total() }} dokumen, halaman {{ $documents->currentPage() }} dari {{ $documents->lastPage() }}</span>
            {{ $documents->links() }}
        </div>
    </div>
@endsection
