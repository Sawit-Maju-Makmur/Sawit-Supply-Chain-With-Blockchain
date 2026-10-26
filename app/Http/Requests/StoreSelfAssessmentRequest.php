<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSelfAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_petani' => ['required', 'string', 'max:100'],
            'nama_kebun'  => ['required', 'string', 'max:100'],
            'lokasi'      => ['required', 'string', 'max:255'],
            'luas_lahan'  => ['required', 'numeric', 'min:0.01', 'max:100000'],
            'tahun_tanam' => ['nullable', 'integer', 'between:1980,' . date('Y')],
            'catatan'     => ['nullable', 'string', 'max:1000'],
        ];

        $allowed = array_keys(config('assessment.options'));

        foreach (config('assessment.categories') as $category) {
            foreach (array_keys($category['questions']) as $key) {
                $rules["answers.$key"] = ['required', Rule::in($allowed)];
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [
            'nama_petani' => 'nama petani',
            'nama_kebun'  => 'nama kebun',
            'luas_lahan'  => 'luas lahan',
            'tahun_tanam' => 'tahun tanam',
        ];

        foreach (config('assessment.categories') as $category) {
            foreach ($category['questions'] as $key => $text) {
                $attributes["answers.$key"] = 'jawaban "' . mb_strimwidth($text, 0, 40, '…') . '"';
            }
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'in'       => ':attribute tidak valid.',
            'max'      => ':attribute terlalu panjang/besar.',
        ];
    }
}
