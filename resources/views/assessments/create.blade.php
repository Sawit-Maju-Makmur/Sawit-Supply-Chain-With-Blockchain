@extends('layouts.app')

@section('title', 'Formulir Self-Assessment')

@section('content')
    <h1 class="page-title">Formulir Mandiri (Self-Assessment)</h1>
    <p class="page-sub">
        Isi data kebun dan jawab setiap pernyataan untuk mengevaluasi tingkat kepatuhan
        praktik sustainability dan traceability kebun sawit Anda.
    </p>

    @if ($errors->any())
        <div class="alert alert-error">
            Terdapat {{ $errors->count() }} isian yang belum benar. Mohon periksa kembali formulir.
        </div>
    @endif

    <form method="POST" action="{{ route('assessments.store') }}" novalidate>
        @csrf

        {{-- ===== Data Kebun ===== --}}
        <div class="card">
            <h2>Data Petani &amp; Kebun</h2>
            <div class="grid">
                <div>
                    <label class="lbl" for="nama_petani">Nama Petani <span class="req">*</span></label>
                    <input type="text" id="nama_petani" name="nama_petani" value="{{ old('nama_petani') }}">
                    @error('nama_petani') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="lbl" for="nama_kebun">Nama Kebun <span class="req">*</span></label>
                    <input type="text" id="nama_kebun" name="nama_kebun" value="{{ old('nama_kebun') }}">
                    @error('nama_kebun') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div class="full">
                    <label class="lbl" for="lokasi">Lokasi (Desa / Kecamatan / Kabupaten) <span class="req">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}">
                    @error('lokasi') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="lbl" for="luas_lahan">Luas Lahan (Ha) <span class="req">*</span></label>
                    <input type="number" step="0.01" min="0" id="luas_lahan" name="luas_lahan" value="{{ old('luas_lahan') }}">
                    @error('luas_lahan') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="lbl" for="tahun_tanam">Tahun Tanam</label>
                    <input type="number" id="tahun_tanam" name="tahun_tanam" min="1980" max="{{ date('Y') }}" value="{{ old('tahun_tanam') }}">
                    @error('tahun_tanam') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ===== Kuesioner ===== --}}
        @foreach ($categories as $category)
            <div class="card">
                <h2>{{ $category['title'] }}</h2>

                @foreach ($category['questions'] as $key => $text)
                    <div class="question">
                        <p>{{ $loop->iteration }}. {{ $text }}</p>
                        <div class="choices">
                            @foreach ($options as $value => $opt)
                                <label class="choice">
                                    <input type="radio" name="answers[{{ $key }}]" value="{{ $value }}"
                                           {{ old("answers.$key") === $value ? 'checked' : '' }}>
                                    <span>{{ $opt['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error("answers.$key") <div class="error-text">{{ $message }}</div> @enderror
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- ===== Catatan ===== --}}
        <div class="card">
            <h2>Catatan Tambahan</h2>
            <textarea name="catatan" rows="3" placeholder="Opsional: kendala atau rencana perbaikan di kebun Anda...">{{ old('catatan') }}</textarea>
            @error('catatan') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="actions">
            <a href="{{ route('assessments.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Kirim &amp; Evaluasi</button>
        </div>
    </form>
@endsection
