<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Modul peta duplikat. Digantikan oleh Lahan (PBI 5/6).
 */
class LandArea extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'polygon_coordinates',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'polygon_coordinates' => 'array',
        ];
    }
}
