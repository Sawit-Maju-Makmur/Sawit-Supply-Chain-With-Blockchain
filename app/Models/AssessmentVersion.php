<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentVersion extends Model
{
    use HasFactory;

    protected $fillable = ['version_number', 'is_active', 'notes', 'created_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(AssessmentCriterion::class, 'version_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
