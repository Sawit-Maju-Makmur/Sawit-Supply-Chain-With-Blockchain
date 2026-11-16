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
        $attributes = $this->validatedAttributes($request);
        if (auth()->check()) {
            $attributes['user_id'] = auth()->id();
        }
        Lahan::create($attributes);
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

        if (! empty($attributes['polygon_coordinates']) && is_string($attributes['polygon_coordinates'])) {
            $attributes['polygon_coordinates'] = json_decode(
                $attributes['polygon_coordinates'],
                true
            );
        } elseif (empty($attributes['polygon_coordinates'])) {
            $attributes['polygon_coordinates'] = null;
        }

        return $attributes;
    }
}