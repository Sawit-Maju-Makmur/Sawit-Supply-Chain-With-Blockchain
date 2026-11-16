<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCriterion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AssessmentCriterionController extends Controller
{
    public function index(): View
    {
        return view('officer.assessment-criteria.index', [
            'categories' => AssessmentCriterion::CATEGORIES,
            'criteriaByCategory' => AssessmentCriterion::query()
                ->orderBy('category')
                ->orderBy('id')
                ->get()
                ->groupBy('category'),
        ]);
    }

    public function updateCategory(Request $request, string $category): RedirectResponse
    {
        abort_unless(array_key_exists($category, AssessmentCriterion::CATEGORIES), 404);

        $existing = AssessmentCriterion::query()
            ->where('category', $category)
            ->get()
            ->keyBy('id');

        $validator = Validator::make($request->all(), [
            'category' => ['required', Rule::in([$category])],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*' => ['required', 'array'],
            'criteria.*.id' => [
                'nullable',
                'integer',
                Rule::exists('assessment_criteria', 'id')->where(fn ($query) => $query->where('category', $category)),
            ],
            'criteria.*.name' => ['required', 'string', 'max:160'],
            'criteria.*.description' => ['nullable', 'string', 'max:2000'],
            'criteria.*.regulatory_reference' => ['nullable', 'string', 'max:255'],
            'criteria.*.weight' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:100'],
            'criteria.*.active' => ['required', 'boolean'],
            'criteria.*.delete' => ['nullable', 'boolean'],
        ], [
            'criteria.*.name.required' => 'Nama kriteria wajib diisi.',
            'criteria.*.weight.decimal' => 'Bobot maksimal menggunakan dua angka desimal.',
        ]);

        $validator->after(function ($validator) use ($request, $existing): void {
            $rows = $request->input('criteria');
            if (! is_array($rows)) {
                return;
            }

            $submittedIds = [];
            $activeWeight = 0.0;

            foreach ($rows as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }

                $id = $row['id'] ?? null;
                if ($id !== null && $id !== '') {
                    if (isset($submittedIds[$id])) {
                        $validator->errors()->add("criteria.$index.id", 'Kriteria yang sama tidak boleh dikirim lebih dari sekali.');
                    }
                    $submittedIds[$id] = true;
                }

                if (filter_var($row['delete'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                    continue;
                }

                if (filter_var($row['active'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                    $weight = is_numeric($row['weight'] ?? null) ? (float) $row['weight'] : 0.0;
                    if ($weight <= 0) {
                        $validator->errors()->add("criteria.$index.weight", 'Bobot kriteria aktif harus lebih besar dari 0%.');
                    }
                    $activeWeight += $weight;
                }
            }

            foreach ($existing as $id => $criterion) {
                if ($criterion->is_active && ! isset($submittedIds[$id])) {
                    $activeWeight += (float) $criterion->weight;
                }
            }

            if (round($activeWeight, 2) !== 100.0) {
                $validator->errors()->add('criteria', 'Jumlah bobot kriteria aktif pada kategori ini harus tepat 100%.');
            }
        });

        $validated = $validator->validate();

        DB::transaction(function () use ($validated, $existing, $category, $request): void {
            foreach ($validated['criteria'] as $row) {
                $id = $row['id'] ?? null;
                $criterion = $id ? $existing->get($id) : null;

                if (($row['delete'] ?? false) && $criterion) {
                    $criterion->delete();
                    continue;
                }

                $criterion ??= new AssessmentCriterion(['category' => $category]);
                $criterion->fill([
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'regulatory_reference' => $row['regulatory_reference'] ?? null,
                    'weight' => $row['weight'],
                    'is_active' => filter_var($row['active'], FILTER_VALIDATE_BOOLEAN),
                    'updated_by' => $request->user()->id,
                ])->save();
            }
        });

        return back()->with('success', 'Kriteria dan bobot kategori '.$category.' berhasil diperbarui.');
    }
}
