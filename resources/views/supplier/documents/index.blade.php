<x-app-layout>
    <div class="page-shell">
        <div class="page-card">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Dokumen Legalitas</h1>
                    <p class="mt-1 text-sm text-slate-600">Unggah berkas sertifikasi untuk diperiksa Sustainability Officer. Berkas hanya dapat diakses oleh Anda dan petugas berwenang.</p>
                </div>
                <a href="{{ route('pemasok.dashboard') }}" class="rounded-full bg-emerald-700 px-4 py-2 text-sm font-semibold text-white">Kembali ke dashboard</a>
            </div>

            @if (session('success'))
                <div class="alert mt-5" role="status">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert mt-5" role="alert">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert mt-5" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php $lahanList = $lahans ?? $plots ?? []; @endphp
            @forelse ($lahanList as $lahan)
                <section class="mt-6 rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-lg font-semibold">{{ $lahan->nama_kebun ?? $lahan->name }}</h2>
                    <p class="text-sm text-slate-600">{{ $lahan->lokasi ?? $lahan->regency ?? 'Lokasi belum diisi' }}</p>

                    @if ($lahan->documents->isNotEmpty())
                        <div class="mt-4 space-y-3">
                            @foreach ($lahan->documents as $document)
                                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-slate-50 p-3">
                                    <div>
                                        <p class="font-medium">{{ $document->type }}</p>
                                        <p class="text-sm text-slate-600">
                                             Status: {{ \App\Models\Document::STATUSES[$document->status] ?? ucfirst($document->status) }}
                                             · Kedaluwarsa: {{ $document->expires_at?->format('d/m/Y') ?? 'Tidak dicantumkan' }}
                                        </p>
                                        @if ($document->review_notes)
                                            <p class="mt-1 text-sm text-rose-700">Catatan officer: {{ $document->review_notes }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if ($document->file_path)
                                            <a href="{{ route('pemasok.documents.download', $document) }}" class="text-sm font-semibold text-emerald-800 underline">Unduh {{ $document->original_filename ?: 'berkas' }}</a>
                                        @endif

                                        @if ($document->status !== 'approved')
                                            <form method="POST" action="{{ route('pemasok.documents.destroy', $document) }}"
                                                onsubmit="return confirm('Hapus dokumen ini? Berkas akan dihapus permanen.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-rose-700 underline">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pemasok.documents.store', $lahan) }}" enctype="multipart/form-data" class="mt-5 grid gap-4 md:grid-cols-2">
                        @csrf
                        <label class="text-sm font-medium">
                            Jenis dokumen
                            <select name="type" required class="mt-1 w-full rounded-xl border-slate-300">
                                <option value="">Pilih jenis dokumen</option>
                                @foreach (\App\Models\Document::TYPES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="text-sm font-medium">
                            Tanggal kedaluwarsa (opsional)
                            <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="mt-1 w-full rounded-xl border-slate-300">
                        </label>
                        <label class="text-sm font-medium md:col-span-2">
                            Berkas legalitas (PDF/JPG/PNG, maks. 10 MB)
                            <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full rounded-xl border border-slate-300 p-2">
                        </label>
                        <div class="md:col-span-2">
                            <button type="submit" class="rounded-full bg-emerald-700 px-5 py-2.5 font-semibold text-white">Kirim untuk diperiksa</button>
                        </div>
                    </form>
                </section>
            @empty
                <p class="mt-6 rounded-xl bg-amber-50 p-4 text-amber-900">Belum ada lahan yang terhubung dengan akun pemasok ini. Silakan <a href="{{ route('lahan.create') }}" class="font-semibold underline">tambah data lahan</a> sebelum mengirim dokumen legalitas.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
