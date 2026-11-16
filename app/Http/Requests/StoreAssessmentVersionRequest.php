<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAssessmentVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:255'],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*.category' => ['required', 'in:Sustainability,Traceability'],
            'criteria.*.name' => ['required', 'string', 'max:255'],
            'criteria.*.question_text' => ['required', 'string', 'max:255'],
            'criteria.*.weight_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                foreach (['Sustainability', 'Traceability'] as $category) {
                    $total = collect($this->input('criteria', []))
                        ->where('category', $category)
                        ->sum(fn (array $criterion): float => (float) ($criterion['weight_percentage'] ?? 0));

                    if (abs($total - 100) > 0.001) {
                        $validator->errors()->add(
                            'criteria',
                            "Total bobot kategori {$category} harus tepat 100%."
                        );
                    }
                }
            },
        ];
    }
}
