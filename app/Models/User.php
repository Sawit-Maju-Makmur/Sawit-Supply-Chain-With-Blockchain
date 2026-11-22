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
<<<<<<< HEAD
    use HasFactory, Notifiable, HasRoles;
=======
    use HasFactory, HasRoles, Notifiable;
>>>>>>> f716e265f6a8063e242afe9c946d7b21e5255ca8

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
<<<<<<< HEAD
        'role',
=======
>>>>>>> f716e265f6a8063e242afe9c946d7b21e5255ca8
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

        if ($this->email) {
            $supplier = Supplier::where('email', $this->email)->first();
            if ($supplier) {
                $supplier->update(['user_id' => $this->id]);
                return $supplier;
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

    public function getRoleAttribute(): ?string
    {
        if (! empty($this->attributes['role'])) {
            return $this->attributes['role'];
        }

        if (method_exists($this, 'getRoleNames')) {
            try {
                $roleName = $this->getRoleNames()->first();
                if ($roleName) {
                    return $roleName;
                }
            } catch (\Throwable $e) {
                // Ignore if Spatie tables are not migrated yet
            }
        }

        return null;
    }
}

