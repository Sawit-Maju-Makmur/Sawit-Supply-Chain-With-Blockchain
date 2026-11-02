@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Daftar Lahan</h1>
    <a href="{{ route('lahan.create') }}" class="bg-green-700 text-white px-4 py-2 rounded">+ Tambah Lahan</a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="p-3">Pemasok</th>
                <th class="p-3">Kebun</th>
                <th class="p-3">Lokasi</th>
                <th class="p-3">Koordinat</th>
                <th class="p-3">Luas (ha)</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($lahans as $l)
            <tr class="border-t">
                <td class="p-3">{{ $l->nama_pemasok }}</td>
                <td class="p-3">{{ $l->nama_kebun }}</td>
                <td class="p-3">{{ $l->lokasi }}</td>
                <td class="p-3">
                    @if ($l->latitude && $l->longitude)
                        <a class="text-blue-600 underline" target="_blank"
                           href="https://www.google.com/maps?q={{ $l->latitude }},{{ $l->longitude }}">
                           {{ $l->latitude }}, {{ $l->longitude }}
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td class="p-3">{{ number_format($l->luas_ha, 2, ',', '.') }}</td>
                <td class="p-3">{{ $l->status_lahan }}</td>
                <td class="p-3 flex gap-2">
                    <a href="{{ route('lahan.edit', $l) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('lahan.destroy', $l) }}" method="POST"
                          onsubmit="return confirm('Hapus data lahan ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="p-6 text-center text-gray-500">Belum ada data lahan.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $lahans->links() }}</div>
@endsection