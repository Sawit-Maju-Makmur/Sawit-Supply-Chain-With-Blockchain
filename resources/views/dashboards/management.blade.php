<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Management</h1>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Anda masuk sebagai tim Management.
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">PBI 9: Kelola Data Pemasok</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Kelola seluruh data pemasok sawit secara sistematis: pencarian nama, filter tipe &amp; status, riwayat perubahan status, dan ekspor data ke Excel/CSV.
                    </p>
                    <a href="{{ route('management.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg text-sm transition-colors shadow">
                        Buka Kelola Pemasok &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
