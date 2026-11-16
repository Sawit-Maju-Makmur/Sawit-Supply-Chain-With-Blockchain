<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** PLACEHOLDER milik PBI 7 (Dokumen untuk Plot). */
class Document extends Model
{
    public const STATUSES = ['approved' => 'Disetujui', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'];

    protected $fillable = ['plot_id', 'type', 'status', 'expires_at'];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }
}

