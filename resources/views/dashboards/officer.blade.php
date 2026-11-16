<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Sustainability Officer') }}
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
                    Anda masuk sebagai <strong>Sustainability Officer</strong>. Pantau profil pemasok, verifikasi peta batas kebun, dan tinjau hasil penilaian self-assessment kepatuhan ISPO/RSPO.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center font-bold mb-3">
                        📋
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Data Pemasok</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Lihat daftar pemasok, sebaran kebun, ringkasan dokumen kepemilikan, dan riwayat status kemitraan.
                    </p>
                </div>
                <a href="{{ route('management.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Lihat Data Pemasok &rarr;
                </a>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">
                        🗺️
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Data Lahan &amp; Poligon</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Akses titik koordinat latitude/longitude dan pemetaan batas poligon lahan sawit pemasok.
                    </p>
                </div>
                <a href="{{ route('lahan.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Lihat Data Lahan &rarr;
                </a>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold mb-3">
                        🌿
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Self-Assessment Kepatuhan</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Pantau riwayat evaluasi mandiri traceability, pencegahan deforestasi, dan praktik sustainability.
                    </p>
                </div>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Lihat Self-Assessment &rarr;
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
