<?php

namespace App\Http\Controllers;

use App\Models\LandArea;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandAreaController extends Controller
{
    public function create(): View
    {
        return view('land_areas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'polygon_coordinates' => ['required', 'json'],
        ]);

        $coordinates = json_decode($validated['polygon_coordinates'], true);

        if (! is_array($coordinates) || count($coordinates) < 3) {
            return back()
                ->withErrors([
                    'polygon_coordinates' => 'Poligon lahan minimal terdiri dari 3 titik koordinat.',
                ])
                ->withInput();
        }

        $userId = auth()->id() ?? 1;

        if (! User::where('id', $userId)->exists()) {
            $fallbackUser = new User();
            $fallbackUser->id = $userId;
            $fallbackUser->name = 'Default User';
            $fallbackUser->email = 'default@example.com';
            $fallbackUser->password = bcrypt('password');
            $fallbackUser->save();
        }

        LandArea::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'polygon_coordinates' => $coordinates,
        ]);

        return redirect()
            ->route('land-areas.create')
            ->with('success', 'Batas lahan berhasil disimpan.');
    }
}
