<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'kode',
        'nama',
        'parent_id',
        'level',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(UnitKerja::class, 'parent_id');
    }

    public function pengelolaKinerjaOrganisasi(): HasMany
    {
        return $this->hasMany(PengelolaKinerjaOrganisasi::class);
    }

    public function penanggungJawabMengisiKinerja(): HasMany
    {
        return $this->hasMany(PenanggungJawabMengisiKinerja::class);
    }

    public function perkinNko(): HasMany
    {
        return $this->hasMany(PerkinNko::class);
    }

    public function penerjemahanIntermediateLv2(): HasMany
    {
        return $this->hasMany(PenerjemahanIntermediateLv2::class);
    }

    public function penerjemahanImmediateLv3(): HasMany
    {
        return $this->hasMany(PenerjemahanImmediateLv3::class);
    }
}
