<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Supplier untuk PBI 9 (Kelola Data Pemasok).
 */
class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const TYPES = ['mandiri' => 'Mandiri', 'plasma' => 'Plasma', 'perusahaan' => 'Perusahaan'];
    public const STATUSES = ['draft' => 'Draft', 'active' => 'Aktif', 'inactive' => 'Nonaktif'];

    protected $fillable = [
        'user_id', 'type', 'name', 'nik', 'npwp', 'address', 'regency', 'phone', 'email',
        'status', 'deactivation_reason', 'deactivated_at',
    ];

    protected function casts(): array
    {
        return ['deactivated_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(SupplierStatusLog::class)->latest('id');
    }

    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        return $q->when($term, fn ($q) => $q->where('name', 'like', '%'.$term.'%'));
    }

    public function scopeOfType(Builder $q, ?string $type): Builder
    {
        return $q->when($type, fn ($q) => $q->where('type', $type));
    }

    public function scopeOfStatus(Builder $q, ?string $status): Builder
    {
        return $q->when($status, fn ($q) => $q->where('status', $status));
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Dipakai PBI lain (AC5): pemasok nonaktif tidak boleh membuat transaksi baru, misalnya
     * di PBI 12: abort_unless($supplier->isActive(), 403, 'Pemasok nonaktif.');
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}

