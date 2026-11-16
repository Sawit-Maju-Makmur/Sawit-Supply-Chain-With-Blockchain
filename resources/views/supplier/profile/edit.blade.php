@extends('layouts.app')

@section('title', 'Profil Usaha Pemasok')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Profil Usaha Pemasok</h1>
        <p class="text-sm text-gray-600 mt-1">Lengkapi data profil usaha pribadi, plasma, atau perusahaan kelapa sawit Anda.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : ($supplier->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
            Status: {{ $supplier->status_label }}
        </span>
        <a href="{{ route('pemasok.dashboard') }}" class="px-4 py-2 border rounded text-sm hover:bg-gray-50">Kembali</a>
    </div>
</div>

@if ($supplier->status === 'inactive' && $supplier->deactivation_reason)
    <div class="mb-6 p-4 rounded bg-red-50 border border-red-200 text-red-800 text-sm">
        <strong>Pemberitahuan Penonaktifan:</strong> {{ $supplier->deactivation_reason }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 rounded bg-red-100 text-red-800 text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pemasok.profile.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block mb-1 text-sm font-semibold" for="type">Tipe Pemasok *</label>
            <select name="type" id="type" class="w-full border rounded p-2.5 focus:ring-green-500 focus:border-green-500" required>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" @selected(old('type', $supplier->type) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="name">Nama Usaha / Petani *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" class="w-full border rounded p-2.5" placeholder="Contoh: Bpk. Supardi / PT Sawit Makmur" required>
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="nik">NIK (Nomor Induk Kependudukan)</label>
            <input type="text" name="nik" id="nik" value="{{ old('nik', $supplier->nik) }}" class="w-full border rounded p-2.5" placeholder="16 digit NIK">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="npwp">NPWP</label>
            <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $supplier->npwp) }}" class="w-full border rounded p-2.5" placeholder="Nomor Pokok Wajib Pajak">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="nib">NIB (Nomor Induk Berusaha)</label>
            <input type="text" name="nib" id="nib" value="{{ old('nib', $supplier->nib) }}" class="w-full border rounded p-2.5" placeholder="Nomor Induk Berusaha">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="regency">Kabupaten / Kota</label>
            <input type="text" name="regency" id="regency" value="{{ old('regency', $supplier->regency) }}" class="w-full border rounded p-2.5" placeholder="Contoh: Kampar / Siak">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="phone">Nomor Telepon / WhatsApp</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full border rounded p-2.5" placeholder="08xxxxxxxxxx">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold" for="email">Email Kontak</label>
            <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" class="w-full border rounded p-2.5" placeholder="nama@email.com">
        </div>

        <div class="md:col-span-2">
            <label class="block mb-1 text-sm font-semibold" for="address">Alamat Lengkap Usaha / Domisili</label>
            <textarea name="address" id="address" rows="3" class="w-full border rounded p-2.5" placeholder="Alamat jalan, desa, kecamatan...">{{ old('address', $supplier->address) }}</textarea>
        </div>
    </div>

    <div class="flex items-center gap-3 pt-4 border-t">
        <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded font-semibold hover:bg-green-800 transition">
            Simpan Perubahan
        </button>
        <a href="{{ route('pemasok.dashboard') }}" class="px-5 py-2.5 border rounded font-semibold text-gray-700 hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
@endsection
