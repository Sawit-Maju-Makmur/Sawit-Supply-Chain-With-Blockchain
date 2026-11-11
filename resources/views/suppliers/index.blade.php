<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Profil & Identitas Pemasok') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data identitas resmi petani atau perusahaan kelapa sawit Anda
                </p>
            </div>
            @if ($supplier)
                <a href="{{ route('suppliers.edit', $supplier) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    {{ __('Edit Data') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($supplier)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Header Card -->
                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-2xl shadow-inner">
                                {{ strtoupper(substr($supplier->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $supplier->type === 'Perusahaan' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $supplier->type }}
                                    </span>
                                    <span class="text-sm text-gray-500">• Terdaftar sejak {{ $supplier->created_at ? $supplier->created_at->format('d M Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @php
                                $statusClasses = match ($supplier->status) {
                                    'Terverifikasi', 'Disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                    'Ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-amber-100 text-amber-800 border-amber-200',
                                };
                            @endphp
                            <div class="flex flex-col md:items-end">
                                <span class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-1">Status Pemasok</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                    <span class="w-2 h-2 rounded-full mr-1.5 {{ str_contains($statusClasses, 'green') ? 'bg-green-500' : (str_contains($statusClasses, 'red') ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                    {{ $supplier->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pemasok</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">{{ $supplier->type }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Perusahaan / Petani</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">{{ $supplier->name }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Induk Berusaha (NIB)</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">
                                    {{ $supplier->nib ?: '-' }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pokok Wajib Pajak (NPWP)</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">
                                    {{ $supplier->npwp ?: '-' }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Telepon</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $supplier->phone }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Akun Terhubung</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">
                                    {{ $supplier->user->email ?? '-' }}
                                </dd>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat Lengkap</dt>
                                <dd class="mt-1 text-base text-gray-900 whitespace-pre-line leading-relaxed">
                                    {{ $supplier->address }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('suppliers.edit', $supplier) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Edit Data') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center p-12">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Profil Pemasok Belum Lengkap</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        Identitas Anda belum terdaftar sebagai pemasok resmi kelapa sawit. Lengkapi data perusahaan atau data pribadi Anda sekarang untuk verifikasi sistem.
                    </p>
                    <a href="{{ route('suppliers.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-green-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('Lengkapi Profil Pemasok') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
