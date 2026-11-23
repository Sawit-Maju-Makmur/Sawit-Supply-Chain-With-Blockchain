<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{

    /** @use HasFactory<UserFactory> */

    use HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function ensureSupplier(): Supplier
    {
        if ($this->relationLoaded('supplier') && $this->supplier) {
            return $this->supplier;
        }

        $supplier = Supplier::where('user_id', $this->id)->first();
        if ($supplier) {
            return $supplier;
        }

        // Tautkan data lama berdasarkan email hanya jika email user sudah terverifikasi
        // DAN supplier tersebut belum dimiliki akun lain.
        if ($this->email && $this->hasVerifiedEmail()) {
            $legacy = Supplier::where('email', $this->email)->whereNull('user_id')->first();

            if ($legacy) {
                $claimed = Supplier::query()
                    ->whereKey($legacy->getKey())
                    ->whereNull('user_id')
                    ->update(['user_id' => $this->id]);

                if ($claimed) {
                    return $legacy->refresh();
                }
            }
        }

        return Supplier::create([
            'user_id' => $this->id,
            'type'    => 'mandiri',
            'name'    => $this->name ?? 'Pemasok',
            'email'   => $this->email,
            'phone'   => $this->phone,
            'address' => $this->address,
            'status'  => Supplier::STATUS_DRAFT,
        ]);
    }

}

