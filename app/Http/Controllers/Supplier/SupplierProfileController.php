<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\UpdateSupplierProfileRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();

        return view('supplier.profile.edit', [
            'supplier' => $supplier,
            'types'    => Supplier::TYPES,
        ]);
    }

    public function update(UpdateSupplierProfileRequest $request): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();

        // Pemasok hanya boleh memperbarui data usaha, BUKAN status atau deactivation_reason
        $supplier->update($request->validated());

        return redirect()->route('pemasok.profile.edit')
            ->with('success', 'Profil usaha pemasok berhasil diperbarui.');
    }
}
