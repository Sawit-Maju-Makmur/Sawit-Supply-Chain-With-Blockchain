<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** PLACEHOLDER milik PBI 5 (Plot / Lahan untuk Supplier). */
class Plot extends Model
{
    public const OWNERSHIP = ['owned' => 'Milik sendiri', 'rented' => 'Sewa', 'partnership' => 'Kemitraan'];

    protected $fillable = ['supplier_id', 'name', 'regency', 'area_ha', 'ownership_status', 'planting_year'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}

