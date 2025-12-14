<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function adminEperforma(): HasOne
    {
        return $this->hasOne(AdminEperforma::class);
    }

    public function pengelolaKinerjaOrganisasi(): HasOne
    {
        return $this->hasOne(PengelolaKinerjaOrganisasi::class);
    }

    public function penanggungJawabMengisiKinerja(): HasOne
    {
        return $this->hasOne(PenanggungJawabMengisiKinerja::class);
    }

    public function isAdminEperforma(): bool
    {
        return $this->role === 'admin_eperforma';
    }

    public function isPKO(): bool
    {
        return $this->role === 'pko';
    }

    public function isPMK(): bool
    {
        return $this->role === 'pmk';
    }
}
