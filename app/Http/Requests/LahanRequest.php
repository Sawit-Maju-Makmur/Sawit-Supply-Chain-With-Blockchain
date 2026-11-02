<?php

namespace App\Http\Requests;

use App\Models\Lahan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class LahanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pemasok' => 'required|string|max:255',
            'nama_kebun'   => 'required|string|max:255',
            'lokasi'       => 'required|string|max:255',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'luas_ha'      => 'required|numeric|min:0.01',
            'status_lahan' => ['required', Rule::in(array_keys(Lahan::STATUS))],
            'no_dokumen'   => 'nullable|string|max:255',
            'keterangan'   => 'nullable|string',
            'polygon_coordinates' => ['nullable', 'json'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $value = $this->input('polygon_coordinates');

            if (! is_string($value) || $value === '') {
                return;
            }

            $coordinates = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return;
            }

            if (! is_array($coordinates) || ! array_is_list($coordinates) || count($coordinates) < 3) {
                $validator->errors()->add(
                    'polygon_coordinates',
                    'Poligon lahan minimal terdiri dari 3 titik koordinat.'
                );

                return;
            }

            foreach ($coordinates as $point) {
                if (
                    ! is_array($point)
                    || ! array_is_list($point)
                    || count($point) !== 2
                    || ! is_numeric($point[0])
                    || ! is_numeric($point[1])
                    || $point[0] < -90
                    || $point[0] > 90
                    || $point[1] < -180
                    || $point[1] > 180
                ) {
                    $validator->errors()->add(
                        'polygon_coordinates',
                        'Setiap titik poligon harus memiliki latitude dan longitude yang valid.'
                    );

                    return;
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'min'      => ':attribute terlalu kecil.',
            'between'  => ':attribute di luar rentang yang valid.',
            'in'       => ':attribute tidak valid.',
        ];
    }
}