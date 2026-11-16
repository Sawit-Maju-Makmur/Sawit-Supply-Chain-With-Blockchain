<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lahan extends Model
{
    public const STATUS = [
        'SHM'    => 'SHM (Sertifikat Hak Milik)',
        'HGU'    => 'HGU (Hak Guna Usaha)',
        'HGB'    => 'HGB (Hak Guna Bangunan)',
        'SKT'    => 'SKT / Girik',
        'Sewa'   => 'Sewa',
        'Lainnya'=> 'Lainnya',
    ];

    protected $fillable = [
        'supplier_id', 'nama_pemasok', 'nama_kebun', 'lokasi', 'latitude', 'longitude',
        'luas_ha', 'status_lahan', 'no_dokumen', 'keterangan', 'polygon_coordinates',
    ];

    protected function casts(): array
    {
        return [
            'polygon_coordinates' => 'array',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}