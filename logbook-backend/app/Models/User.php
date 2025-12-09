<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'guid',
        'fpid',
        'nama_pegawai',
        'name',
        'role',
        'parent_id',
        'email',
        'password',
        'nip_nrp',
        'pangkat_golongan',
        'golongan',
        'kode_peta_jabatan',
        'nama_jabatan',
        'kelas',
        'kode_unit_organisasi',
        'nama_unit_organisasi',
        'nama_lengkap_peta_jabatan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Hierarchical Relationships

    /**
     * Parent user (atasan)
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Children users (bawahan)
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Role-specific Relationships

    /**
     * Get PMK list (untuk Ka-Unit)
     */
    public function pmkList()
    {
        return $this->hasMany(User::class, 'parent_id')->where('role', 'pmk');
    }

    /**
     * Get PK/O list (untuk PMK)
     */
    public function pkoList()
    {
        return $this->hasMany(User::class, 'parent_id')->where('role', 'pko');
    }

    /**
     * Get Ka-Unit (untuk PMK)
     */
    public function kaUnit()
    {
        return $this->belongsTo(User::class, 'parent_id')->where('role', 'ka-unit');
    }

    /**
     * Get PMK supervisor (untuk PK/O)
     */
    public function pmk()
    {
        return $this->belongsTo(User::class, 'parent_id')->where('role', 'pmk');
    }

    // Helper Methods

    /**
     * Check if user is Ka-Unit
     */
    public function isKaUnit(): bool
    {
        return $this->role === 'ka-unit';
    }

    /**
     * Check if user is PMK
     */
    public function isPmk(): bool
    {
        return $this->role === 'pmk';
    }

    /**
     * Check if user is PK/O
     */
    public function isPko(): bool
    {
        return $this->role === 'pko';
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get all subordinates recursively (semua bawahan)
     */
    public function allSubordinates()
    {
        return $this->children()->with('allSubordinates');
    }

    // Existing Relationships
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
