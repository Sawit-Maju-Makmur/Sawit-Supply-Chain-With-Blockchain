<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSelfAssessmentRequest;
use App\Models\SelfAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SelfAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();
        $assessments = $supplier->selfAssessments()->latest()->paginate(10);

        return view('assessments.index', compact('assessments', 'supplier'));
    }

    public function create(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();

        return view('assessments.create', [
            'supplier'   => $supplier,
            'categories' => config('assessment.categories'),
            'options'    => config('assessment.options'),
        ]);
    }

    public function store(StoreSelfAssessmentRequest $request): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();
        $data = $request->validated();
        $result = SelfAssessment::calculate($data['answers']);

        $data['supplier_id'] = $supplier->id;
        $data['user_id']     = $request->user()->id;

        $assessment = SelfAssessment::create($data + $result);

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Formulir self-assessment berhasil disimpan dan dievaluasi.');
    }

    public function show(Request $request, SelfAssessment $assessment): View
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($assessment->supplier_id === $supplier->id, 404);

        return view('assessments.show', [
            'assessment' => $assessment,
            'categories' => config('assessment.categories'),
            'options'    => config('assessment.options'),
        ]);
    }

    public function destroy(Request $request, SelfAssessment $assessment): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($assessment->supplier_id === $supplier->id, 404);

        $assessment->delete();

        return redirect()
            ->route('assessments.index')
            ->with('success', 'Data self-assessment berhasil dihapus.');
    }
}
