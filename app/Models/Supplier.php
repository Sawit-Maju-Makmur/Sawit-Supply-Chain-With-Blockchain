<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'nib',
        'npwp',
        'address',
        'phone',
        'status',
    ];

    /**
     * Relasi ke model User (Pemasok dimiliki oleh satu User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
