<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSelfAssessmentRequest;
use App\Models\SelfAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SelfAssessmentController extends Controller
{
    public function index(): View
    {
        $assessments = SelfAssessment::latest()->paginate(10);

        return view('assessments.index', compact('assessments'));
    }

    public function create(): View
    {
        return view('assessments.create', [
            'categories' => config('assessment.categories'),
            'options'    => config('assessment.options'),
        ]);
    }

    public function store(StoreSelfAssessmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $result = SelfAssessment::calculate($data['answers']);

        $assessment = SelfAssessment::create($data + $result);

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', 'Formulir self-assessment berhasil disimpan dan dievaluasi.');
    }

    public function show(SelfAssessment $assessment): View
    {
        return view('assessments.show', [
            'assessment' => $assessment,
            'categories' => config('assessment.categories'),
            'options'    => config('assessment.options'),
        ]);
    }

    public function destroy(SelfAssessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()
            ->route('assessments.index')
            ->with('success', 'Data self-assessment berhasil dihapus.');
    }
}
