@extends('layouts.app')

@section('title', 'Catat Hasil Panen')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">🌿 Hasil Panen</p>
        <h1 class="dashboard-title">Catat Hasil Panen Baru</h1>
    </div>
    <a href="{{ route('pemasok.harvests.index') }}"
       style="color:var(--sips-muted); font-size:0.9rem; display:inline-flex; align-items:center; gap:0.4rem;">
        ← Kembali ke Riwayat
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0; padding-left:1.2rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Informasi PBI --}}
<div style="
    background: linear-gradient(135deg, #e0f7ea 0%, #f1fdf5 100%);
    border: 1px solid #a7f3d0;
    border-radius: 1rem;
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
">
    <span style="font-size:1.5rem; line-height:1;">🔗</span>
    <div>
        <p style="margin:0 0 0.3rem; font-weight:700; color:var(--sips-primary-strong);">
            QR Code Terintegrasi Blockchain
        </p>
        <p style="margin:0; font-size:0.88rem; color:var(--sips-muted); line-height:1.6;">
            Setelah data disimpan, sistem akan mencatatkan detail panen ke blockchain dan
            menerbitkan <strong>QR Code unik</strong> yang dapat dipindai untuk memverifikasi
            asal-usul hasil panen Anda.
        </p>
    </div>
</div>

<form method="POST" action="{{ route('pemasok.harvests.store') }}" id="form-panen">
    @csrf

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

        {{-- Lahan / Kebun --}}
        <div style="grid-column:1 / -1;">
            <label for="lahan_id" style="display:block; font-weight:700; margin-bottom:0.4rem; font-size:0.9rem;">
                Lahan / Kebun <span style="color:#dc2626;">*</span>
            </label>
            @if ($lahans->isEmpty())
                <div class="alert alert-error" style="margin:0;">
                    Anda belum memiliki lahan terdaftar.
                    <a href="{{ route('lahan.create') }}" style="font-weight:700; color:var(--sips-primary);">Tambah Lahan</a>
                </div>
                <input type="hidden" name="lahan_id" value="">
            @else
                <select name="lahan_id" id="lahan_id" required
                        style="width:100%; padding:0.65rem 0.9rem; border:1px solid var(--sips-border);
                               border-radius:0.65rem; font-size:0.9rem; background:#fff; outline:none;">
                    <option value="">-- Pilih Lahan --</option>
                    @foreach ($lahans as $lahan)
                        <option value="{{ $lahan->id }}" {{ old('lahan_id') == $lahan->id ? 'selected' : '' }}>
                            {{ $lahan->nama_kebun }} – {{ $lahan->lokasi }} ({{ $lahan->luas_ha }} ha)
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        {{-- Jenis Komoditas --}}
        <div>
            <label for="jenis_komoditas" style="display:block; font-weight:700; margin-bottom:0.4rem; font-size:0.9rem;">
                Jenis Komoditas <span style="color:#dc2626;">*</span>
            </label>
            <select name="jenis_komoditas" id="jenis_komoditas" required
                    style="width:100%; padding:0.65rem 0.9rem; border:1px solid var(--sips-border);
                           border-radius:0.65rem; font-size:0.9rem; background:#fff; outline:none;">
                @foreach (\App\Models\Harvest::JENIS_KOMODITAS as $value => $label)
                    <option value="{{ $value }}" {{ old('jenis_komoditas', 'Tandan Buah Segar (TBS)') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah (kg) --}}
        <div>
            <label for="jumlah_kg" style="display:block; font-weight:700; margin-bottom:0.4rem; font-size:0.9rem;">
                Jumlah (kg) <span style="color:#dc2626;">*</span>
            </label>
            <input type="number" name="jumlah_kg" id="jumlah_kg"
                   value="{{ old('jumlah_kg') }}"
                   step="0.01" min="0.01" max="999999" required
                   placeholder="Contoh: 1250.00"
                   style="width:100%; padding:0.65rem 0.9rem; border:1px solid var(--sips-border);
                          border-radius:0.65rem; font-size:0.9rem; outline:none; box-sizing:border-box;">
        </div>

        {{-- Tanggal Panen --}}
        <div>
            <label for="tanggal_panen" style="display:block; font-weight:700; margin-bottom:0.4rem; font-size:0.9rem;">
                Tanggal Panen <span style="color:#dc2626;">*</span>
            </label>
            <input type="date" name="tanggal_panen" id="tanggal_panen"
                   value="{{ old('tanggal_panen', date('Y-m-d')) }}"
                   max="{{ date('Y-m-d') }}" required
                   style="width:100%; padding:0.65rem 0.9rem; border:1px solid var(--sips-border);
                          border-radius:0.65rem; font-size:0.9rem; outline:none; box-sizing:border-box;">
        </div>

        {{-- Keterangan --}}
        <div style="grid-column:1 / -1;">
            <label for="keterangan" style="display:block; font-weight:700; margin-bottom:0.4rem; font-size:0.9rem;">
                Keterangan (opsional)
            </label>
            <textarea name="keterangan" id="keterangan" rows="3"
                      placeholder="Catatan tambahan seperti kondisi cuaca, kualitas buah, dll."
                      style="width:100%; padding:0.65rem 0.9rem; border:1px solid var(--sips-border);
                             border-radius:0.65rem; font-size:0.9rem; resize:vertical; outline:none; box-sizing:border-box;">{{ old('keterangan') }}</textarea>
        </div>

    </div>

    {{-- Informasi supplier (read-only) --}}
    <div style="margin-top:1.25rem; padding:1rem; background:var(--sips-surface-soft);
                border:1px solid var(--sips-border); border-radius:0.85rem;">
        <p style="margin:0 0 0.5rem; font-weight:700; font-size:0.88rem; color:var(--sips-primary);">
            ℹ️ Informasi Pemasok (otomatis)
        </p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; font-size:0.85rem; color:var(--sips-muted);">
            <div><strong>Nama:</strong> {{ $supplier->name }}</div>
            <div><strong>Tipe:</strong> {{ $supplier->type_label }}</div>
            <div><strong>NIK:</strong> {{ $supplier->nik ?? '-' }}</div>
            <div><strong>NPWP:</strong> {{ $supplier->npwp ?? '-' }}</div>
        </div>
    </div>

    <div style="margin-top:1.5rem; display:flex; gap:1rem; justify-content:flex-end;">
        <a href="{{ route('pemasok.harvests.index') }}"
           style="padding:0.65rem 1.4rem; border-radius:999px; border:1px solid var(--sips-border);
                  font-size:0.9rem; font-weight:600; color:var(--sips-muted); background:#fff;">
            Batal
        </a>
        @if (!$lahans->isEmpty())
            <button type="submit" class="pill-link"
                    style="font-size:0.9rem; padding:0.65rem 1.6rem; border:0; cursor:pointer;">
                🔗 Simpan & Terbitkan QR Code
            </button>
        @endif
    </div>
</form>
@endsection
