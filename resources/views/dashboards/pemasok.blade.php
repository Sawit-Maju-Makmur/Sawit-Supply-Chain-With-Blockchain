<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Pemasok') }}
            </h2>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-md border border-red-200 transition">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Selamat datang, {{ auth()->user()->name }}!</h3>
                <p class="text-sm text-gray-600">
                    Anda masuk sebagai <strong>Pemasok Sawit</strong>. Kelola data lahan kebun, lakukan pemetaan koordinat poligon, serta lengkapi formulir evaluasi mandiri (self-assessment).
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center font-bold mb-3">
                        🌴
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Kelola Data Lahan</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Catat dan kelola informasi legalitas lahan, luas hektar, nomor dokumen, dan lokasi kebun Anda.
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('lahan.index') }}" class="inline-flex items-center px-3 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                        Daftar Lahan
                    </a>
                    <a href="{{ route('lahan.create') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm transition border">
                        + Tambah
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">
                        📍
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Pemetaan Batas (Polygon)</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Gunakan peta digital interaktif untuk menggambar koordinat poligon batas lahan kebun sawit.
                    </p>
                </div>
                <a href="{{ route('land-areas.create') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Buka Peta Poligon &rarr;
                </a>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold mb-3">
                        📝
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Formulir Self-Assessment</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Isi kuesioner ketertelusuran dan praktik ramah lingkungan untuk mengetahui tingkat kepatuhan kebun.
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-3 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                        Riwayat
                    </a>
                    <a href="{{ route('assessments.create') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm transition border">
                        + Isi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
