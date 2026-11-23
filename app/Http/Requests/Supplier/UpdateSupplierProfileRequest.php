<?php

namespace App\Http\Requests\Supplier;

use App\Models\Supplier;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        // Buang spasi agar "3201 2345 ..." tidak ditolak hanya karena format penulisan.
        foreach (['nik', 'npwp'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([$field => preg_replace('/\s+/', '', $this->input($field))]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'type'    => ['required', Rule::in(array_keys(Supplier::TYPES))],
            'name'    => ['required', 'string', 'max:255'],
            'nik'     => ['nullable', 'digits:16'],
            'npwp'    => [
                'nullable', 'string', 'max:25', 'regex:/^[0-9.\-]+$/',
                function (string $attribute, mixed $value, Closure $fail) {
                    $digits = strlen(preg_replace('/\D/', '', (string) $value));
                    if (! in_array($digits, [15, 16], true)) {
                        $fail('NPWP harus terdiri dari 15 atau 16 digit.');
                    }
                },
            ],
            'nib'     => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'regency' => ['nullable', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'email'   => ['nullable', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.digits'  => 'NIK harus terdiri dari 16 digit angka.',
            'npwp.regex'  => 'NPWP hanya boleh berisi angka, titik, dan tanda hubung.',
            'npwp.max'    => 'NPWP maksimal 25 karakter.',
        ];
    }
}