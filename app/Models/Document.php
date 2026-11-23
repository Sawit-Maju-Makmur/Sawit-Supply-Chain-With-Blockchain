<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    public const STATUSES = ['approved' => 'Disetujui', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'];

    public const TYPES = [
        'SHM'     => 'SHM (Sertifikat Hak Milik)',
        'HGU'     => 'HGU (Hak Guna Usaha)',
        'HGB'     => 'HGB (Hak Guna Bangunan)',
        'SKT'     => 'SKT / Girik',
        'STDB'    => 'STDB (Surat Tanda Daftar Budidaya)',
        'ISPO'    => 'ISPO',
        'RSPO'    => 'RSPO',
        'ISCC'    => 'ISCC',
        'Lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'lahan_id',
        'plot_id',
        'type',
        'file_path',
        'original_filename',
        'status',
        'expires_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }

    /** @deprecated Gunakan lahan() */
    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

