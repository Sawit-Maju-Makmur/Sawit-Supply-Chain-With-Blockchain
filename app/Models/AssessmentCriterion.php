<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentCriterion extends Model
{
    public const CATEGORY_SUSTAINABILITY = 'sustainability';
    public const CATEGORY_TRACEABILITY = 'traceability';

    public const CATEGORIES = [
        self::CATEGORY_SUSTAINABILITY => 'Sustainability',
        self::CATEGORY_TRACEABILITY => 'Traceability',
    ];

    protected $fillable = [
        'category',
        'name',
        'description',
        'regulatory_reference',
        'weight',
        'is_active',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
