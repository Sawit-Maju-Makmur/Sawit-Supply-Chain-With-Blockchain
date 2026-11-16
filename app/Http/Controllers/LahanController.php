<?php

namespace App\Http\Controllers;

use App\Http\Requests\LahanRequest;
use App\Models\Lahan;
use Illuminate\Http\Request;

class LahanController extends Controller
{
    public function index(Request $request)
    {
        $supplier = $request->user()->ensureSupplier();
        $lahans = $supplier->lahans()->latest()->paginate(10);

        return view('lahan.index', compact('lahans', 'supplier'));
    }

    public function create()
    {
        return view('lahan.create');
    }

    public function store(LahanRequest $request)
    {
        $supplier = $request->user()->ensureSupplier();
        $attributes = $this->validatedAttributes($request);
        $attributes['supplier_id'] = $supplier->id;
        $attributes['nama_pemasok'] = $supplier->name ?: ($request->input('nama_pemasok') ?: 'Pemasok');

        $supplier->lahans()->create($attributes);

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil disimpan.');
    }

    public function show(Request $request, Lahan $lahan)
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($lahan->supplier_id === $supplier->id, 404);

        return redirect()->route('lahan.edit', $lahan);
    }

    public function edit(Request $request, Lahan $lahan)
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($lahan->supplier_id === $supplier->id, 404);

        return view('lahan.edit', compact('lahan'));
    }

    public function update(LahanRequest $request, Lahan $lahan)
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($lahan->supplier_id === $supplier->id, 404);

        $attributes = $this->validatedAttributes($request);
        $attributes['supplier_id'] = $supplier->id;
        $attributes['nama_pemasok'] = $supplier->name ?: ($request->input('nama_pemasok') ?: 'Pemasok');

        $lahan->update($attributes);

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui.');
    }

    public function destroy(Request $request, Lahan $lahan)
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($lahan->supplier_id === $supplier->id, 404);

        $lahan->delete();

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil dihapus.');
    }

    private function validatedAttributes(LahanRequest $request): array
    {
        $attributes = $request->validated();

        if (isset($attributes['polygon_coordinates'])) {
            $attributes['polygon_coordinates'] = json_decode(
                $attributes['polygon_coordinates'],
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        return $attributes;
    }
}
