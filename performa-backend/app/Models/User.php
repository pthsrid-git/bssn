<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'guid',
        'uuid',
        'name',
        'fullname',
        'email',
        'fpid',
        'nip',
        'pangkat',
        'jabatan',
        'unit_kerja',
        'password',
        'role',
        'parent_id',
        'kode_unit_organisasi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }

    /**
     * Append custom attributes to JSON
     * ⚠️ HANYA append 'roles', JANGAN append 'permissions' karena akan conflict
     */
    protected $appends = [
        'roles',
    ];

    /**
     * Boot method - auto generate UUID and GUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
            if (empty($user->guid)) {
                $user->guid = (string) Str::uuid();
            }
        });
    }

    // ========================================
    // Frontend Compatibility Accessors
    // ========================================

    /**
     * Get roles as array
     */
    public function getRolesAttribute(): array
    {
        return [$this->role];
    }

    /**
     * Get permissions based on role
     * ⚠️ BUKAN accessor, ini method biasa agar tidak auto-append
     */
    public function getRolePermissions(): array
    {
        $permissions = [
            'ruangPribadi' => [],
            'ruangKerja' => []
        ];

        // Add permissions based on role
        switch ($this->role) {
            case 'pko':  // PMO
                $permissions['ruangPribadi'][] = 'project.role.pribadi.dashboard';
                $permissions['ruangKerja'][] = 'project.role.kerja.pko';
                break;

            case 'pmk':  // Katim - hanya dashboard di ruang pribadi
                $permissions['ruangPribadi'][] = 'project.role.pribadi.dashboard';
                $permissions['ruangKerja'][] = 'project.role.kerja.pmk';
                break;

            case 'admin':  // Admin - hanya ruang kerja
                $permissions['ruangPribadi'][] = 'project.role.pribadi.dashboard';
                $permissions['ruangKerja'][] = 'project.role.kerja.admin';
                break;

            default:
                // No permissions
                break;
        }

        return $permissions;
    }

    // ========================================
    // JWT Methods
    // ========================================

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // ========================================
    // Hierarchical Relationships
    // ========================================

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

    // ========================================
    // Role-specific Relationships
    // ========================================

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

    // ========================================
    // Helper Methods
    // ========================================

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

    // ========================================
    // Existing Relationships
    // ========================================

    /**
     * Get user's logbooks
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
