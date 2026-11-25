<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Harvest extends Model
{
    public const BLOCKCHAIN_STATUSES = [
        'pending'   => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'failed'    => 'Gagal',
    ];

    public const JENIS_KOMODITAS = [
        'Tandan Buah Segar (TBS)'       => 'Tandan Buah Segar (TBS)',
        'Minyak Sawit Mentah (CPO)'     => 'Minyak Sawit Mentah (CPO)',
        'Palm Kernel'                   => 'Palm Kernel',
        'Lainnya'                       => 'Lainnya',
    ];

    protected $fillable = [
        'supplier_id',
        'lahan_id',
        'kode_panen',
        'jenis_komoditas',
        'jumlah_kg',
        'tanggal_panen',
        'keterangan',
        'tx_hash',
        'block_number',
        'blockchain_status',
        'blockchain_confirmed_at',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_panen'            => 'date',
            'blockchain_confirmed_at'  => 'datetime',
            'jumlah_kg'                => 'float',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }

    // ─── Boot: auto-generate kode_panen & qr_token ───────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Harvest $harvest) {
            if (empty($harvest->kode_panen)) {
                $harvest->kode_panen = static::generateKodePanen();
            }
            if (empty($harvest->qr_token)) {
                $harvest->qr_token = Str::random(48);
            }
        });
    }

    private static function generateKodePanen(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('HRV-%s-%04d', $year, $count);
    }

    // ─── Blockchain simulation ────────────────────────────────────────────────

    /**
     * Simulasikan pencatatan ke blockchain (hash deterministik dari data panen).
     * Pada produksi, ganti dengan panggilan ke smart contract / Web3 library.
     */
    public function recordToBlockchain(): void
    {
        $payload = implode('|', [
            $this->kode_panen,
            $this->supplier_id,
            $this->lahan_id,
            $this->jenis_komoditas,
            $this->jumlah_kg,
            $this->tanggal_panen->toDateString(),
        ]);

        $this->update([
            'tx_hash'                  => '0x' . hash('sha256', $payload . config('app.key')),
            'block_number'             => random_int(18_000_000, 20_000_000),
            'blockchain_status'        => 'confirmed',
            'blockchain_confirmed_at'  => now(),
        ]);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getBlockchainStatusLabelAttribute(): string
    {
        return self::BLOCKCHAIN_STATUSES[$this->blockchain_status] ?? ucfirst((string) $this->blockchain_status);
    }

    public function getQrUrlAttribute(): string
    {
        return route('harvest.verify', ['token' => $this->qr_token]);
    }
}
