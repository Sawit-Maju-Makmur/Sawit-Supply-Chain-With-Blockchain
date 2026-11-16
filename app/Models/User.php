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
        'role',
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

