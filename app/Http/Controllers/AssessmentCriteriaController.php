<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssessmentVersionRequest;
use App\Models\AssessmentVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssessmentCriteriaController extends Controller
{
    public function index(): View
    {
        $activeVersion = AssessmentVersion::with('criteria')
            ->where('is_active', true)
            ->latest('version_number')
            ->first();

        $versions = AssessmentVersion::with('criteria')
            ->orderByDesc('version_number')
            ->get();
        $displayVersion = request()->integer('version')
            ? $versions->firstWhere('id', request()->integer('version'))
            : $activeVersion;
        $displayVersion ??= $activeVersion;
        $isReadOnly = (bool) $displayVersion && $displayVersion->id !== $activeVersion?->id;

        return view('assessment-criteria.index', compact('activeVersion', 'versions', 'displayVersion', 'isReadOnly'));
    }

    public function store(StoreAssessmentVersionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $latestNumber = AssessmentVersion::lockForUpdate()->max('version_number') ?? 0;

            AssessmentVersion::where('is_active', true)->update(['is_active' => false]);

            $version = AssessmentVersion::create([
                'version_number' => $latestNumber + 1,
                'is_active' => true,
                'notes' => $request->validated('notes'),
                'created_by' => auth()->id(),
            ]);

            $version->criteria()->createMany($request->validated('criteria'));
        });

        return to_route('assessment-criteria.index')
            ->with('success', 'Kriteria berhasil disimpan sebagai versi berikutnya.');
    }
}
