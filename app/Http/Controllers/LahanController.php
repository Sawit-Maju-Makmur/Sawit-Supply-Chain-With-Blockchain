<?php

namespace App\Http\Controllers;

use App\Http\Requests\LahanRequest;
use App\Models\Lahan;

class LahanController extends Controller
{
    public function index()
    {
        $lahans = Lahan::latest()->paginate(10);
        return view('lahan.index', compact('lahans'));
    }

    public function create()
    {
        return view('lahan.create');
    }

    public function store(LahanRequest $request)
    {
        Lahan::create($this->validatedAttributes($request));
        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil disimpan.');
    }

    public function show(Lahan $lahan)
    {
        return redirect()->route('lahan.edit', $lahan);
    }

    public function edit(Lahan $lahan)
    {
        return view('lahan.edit', compact('lahan'));
    }

    public function update(LahanRequest $request, Lahan $lahan)
    {
        $lahan->update($this->validatedAttributes($request));
        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui.');
    }

    public function destroy(Lahan $lahan)
    {
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