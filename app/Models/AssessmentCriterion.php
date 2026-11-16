<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentCriterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'category',
        'name',
        'question_text',
        'weight_percentage',
    ];

    protected function casts(): array
    {
        return ['weight_percentage' => 'decimal:2'];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(AssessmentVersion::class, 'version_id');
    }
}
