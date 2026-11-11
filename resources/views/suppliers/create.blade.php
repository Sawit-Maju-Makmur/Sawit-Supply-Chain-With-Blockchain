<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Daftar Profil Pemasok Resmi') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Lengkapi identitas pribadi atau perusahaan kelapa sawit Anda untuk didaftarkan ke sistem
                </p>
            </div>
            <a href="{{ route('suppliers.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- Jenis Pemasok -->
                    <div>
                        <x-input-label for="type" :value="__('Jenis Pemasok')" />
                        <select id="type" name="type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('type') border-red-500 @enderror"
                                required>
                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>-- Pilih Jenis Pemasok --</option>
                            <option value="Perusahaan" {{ old('type') === 'Perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                            <option value="Pribadi" {{ old('type') === 'Pribadi' ? 'selected' : '' }}>Pribadi (Petani Swadaya)</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Nama -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Perusahaan / Petani')" />
                        <x-text-input id="name" name="name" type="text"
                                      class="mt-1 block w-full @error('name') border-red-500 @enderror"
                                      :value="old('name')"
                                      placeholder="Contoh: PT Sawit Nusantara Jaya atau Bpk. Supardi"
                                      required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIB -->
                        <div>
                            <div class="flex items-center justify-between">
                                <x-input-label for="nib" :value="__('Nomor Induk Berusaha (NIB)')" />
                                <span class="text-xs text-gray-500">Opsional</span>
                            </div>
                            <x-text-input id="nib" name="nib" type="text"
                                          class="mt-1 block w-full @error('nib') border-red-500 @enderror"
                                          :value="old('nib')"
                                          placeholder="13 digit NIB" />
                            <x-input-error :messages="$errors->get('nib')" class="mt-2" />
                        </div>

                        <!-- NPWP -->
                        <div>
                            <div class="flex items-center justify-between">
                                <x-input-label for="npwp" :value="__('Nomor Pokok Wajib Pajak (NPWP)')" />
                                <span class="text-xs text-gray-500">Opsional</span>
                            </div>
                            <x-text-input id="npwp" name="npwp" type="text"
                                          class="mt-1 block w-full @error('npwp') border-red-500 @enderror"
                                          :value="old('npwp')"
                                          placeholder="15 atau 16 digit NPWP" />
                            <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <x-input-label for="phone" :value="__('Nomor Telepon / WhatsApp Aktif')" />
                        <x-text-input id="phone" name="phone" type="text"
                                      class="mt-1 block w-full @error('phone') border-red-500 @enderror"
                                      :value="old('phone')"
                                      placeholder="Contoh: 081234567890"
                                      required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Alamat -->
                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap Kantor / Domisili')" />
                        <textarea id="address" name="address" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('address') border-red-500 @enderror"
                                  placeholder="Tuliskan nama jalan, RT/RW, desa/kelurahan, kecamatan, kabupaten/kota, dan provinsi..."
                                  required>{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('suppliers.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button class="bg-green-700 hover:bg-green-800 focus:bg-green-800 active:bg-green-900">
                            {{ __('Simpan Data Pemasok') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
