<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Menampilkan halaman profil pemasok milik user yang sedang login.
     * Jika sudah ada, tampilkan detailnya.
     * Jika belum ada data, view akan menampilkan opsi untuk melengkapi profil pemasok.
     */
    public function index(): View
    {
        $supplier = Auth::user()->supplier;

        return view('suppliers.index', compact('supplier'));
    }

    /**
     * Menampilkan form tambah data profil pemasok.
     */
    public function create(): View|RedirectResponse
    {
        $supplier = Auth::user()->supplier;

        if ($supplier) {
            return redirect()->route('suppliers.edit', $supplier)
                ->with('info', 'Anda sudah memiliki data pemasok. Anda dapat mengubah data melalui form ini.');
        }

        return view('suppliers.create');
    }

    /**
     * Validasi input dan simpan data ke database dengan user_id dari Auth::id().
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Data identitas pemasok sudah terdaftar.');
        }

        $validated = $request->validate([
            'type'    => ['required', 'string', 'in:Perusahaan,Pribadi'],
            'name'    => ['required', 'string', 'max:255'],
            'nib'     => ['nullable', 'string', 'max:50'],
            'npwp'    => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'phone'   => ['required', 'string', 'max:20'],
        ], [
            'type.required'    => 'Jenis pemasok wajib dipilih.',
            'type.in'          => 'Jenis pemasok harus Perusahaan atau Pribadi.',
            'name.required'    => 'Nama perusahaan atau petani wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'phone.required'   => 'Nomor telepon aktif wajib diisi.',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status']  = 'Menunggu Verifikasi';

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Identitas profil pemasok berhasil didaftarkan dan menunggu verifikasi.');
    }

    /**
     * Menampilkan detail profil pemasok jika diakses via route model binding.
     */
    public function show(Supplier $supplier): View|RedirectResponse
    {
        if ($supplier->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses ke data pemasok ini.');
        }

        return view('suppliers.index', compact('supplier'));
    }

    /**
     * Menampilkan form edit data pemasok milik user yang sedang login.
     */
    public function edit(Supplier $supplier): View
    {
        if ($supplier->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit data pemasok ini.');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Validasi dan perbarui data ke database.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        if ($supplier->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk memperbarui data pemasok ini.');
        }

        $validated = $request->validate([
            'type'    => ['required', 'string', 'in:Perusahaan,Pribadi'],
            'name'    => ['required', 'string', 'max:255'],
            'nib'     => ['nullable', 'string', 'max:50'],
            'npwp'    => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'phone'   => ['required', 'string', 'max:20'],
        ], [
            'type.required'    => 'Jenis pemasok wajib dipilih.',
            'type.in'          => 'Jenis pemasok harus Perusahaan atau Pribadi.',
            'name.required'    => 'Nama perusahaan atau petani wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'phone.required'   => 'Nomor telepon aktif wajib diisi.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Data profil pemasok berhasil diperbarui.');
    }
}
