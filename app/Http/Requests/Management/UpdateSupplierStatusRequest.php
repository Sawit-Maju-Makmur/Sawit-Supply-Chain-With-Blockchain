<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware role di route
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:active,inactive'],
            'reason' => ['nullable', 'required_if:status,inactive', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'reason.required_if' => 'Alasan penonaktifan wajib diisi.',
            'reason.max' => 'Alasan maksimal 500 karakter.',
        ];
    }
}

