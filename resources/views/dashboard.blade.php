<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
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
                    Anda berhasil masuk ke platform Sawit Maju Makmur Supply Chain.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
