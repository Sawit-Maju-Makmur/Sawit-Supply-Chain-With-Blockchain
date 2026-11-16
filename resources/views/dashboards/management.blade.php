<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Management') }}
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
                    Anda masuk sebagai tim <strong>Management</strong>. Anda memiliki kewenangan penuh mengelola data pemasok, menyetujui/menonaktifkan akun, dan mengekspor data ke Excel/CSV.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center font-bold mb-3">
                        🏢
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">PBI 9: Kelola Data Pemasok</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Pencarian nama, filter tipe &amp; status, audit log perubahan status, dan ekspor data CSV/Excel.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('management.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                        Kelola Pemasok &rarr;
                    </a>
                    <a href="{{ route('management.suppliers.export') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm transition border">
                        Export CSV
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">
                        🌱
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Data Lahan &amp; Kebun</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Tinjau seluruh data lahan terdaftar, status sertifikat (SHM/HGU/dll), dan koordinat pemetaan.
                    </p>
                </div>
                <a href="{{ route('lahan.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Lihat Data Lahan &rarr;
                </a>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold mb-3">
                        📊
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Self-Assessment Kepatuhan</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Pantau persentase kepatuhan rantai pasok terhadap standar keberlanjutan dan ketertelusuran.
                    </p>
                </div>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition">
                    Lihat Assessment &rarr;
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
