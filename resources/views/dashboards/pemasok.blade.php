<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pemasok') }}
        </h2>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-medium text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h3>
            <p class="mt-1 text-sm text-gray-600">
                Kelola identitas resmi pemasok, lahan perkebunan sawit, dan formulir self-assessment keberlanjutan Anda melalui dashboard ini.
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ auth()->user()->supplier ? 'Lihat Profil Pemasok' : 'Lengkapi Profil Pemasok' }}
                </a>
                <a href="{{ route('lahan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Kelola Lahan
                </a>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                    Self-Assessment
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
