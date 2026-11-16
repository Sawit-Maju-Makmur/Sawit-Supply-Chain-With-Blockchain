<?php

namespace App\Http\Requests\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type'    => ['required', Rule::in(array_keys(Supplier::TYPES))],
            'name'    => ['required', 'string', 'max:255'],
            'nik'     => ['nullable', 'string', 'max:20'],
            'npwp'    => ['nullable', 'string', 'max:30'],
            'nib'     => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'regency' => ['nullable', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'email'   => ['nullable', 'email', 'max:255'],
        ];
    }
}
