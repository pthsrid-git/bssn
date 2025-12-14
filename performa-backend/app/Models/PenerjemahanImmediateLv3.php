<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerjemahanImmediateLv3 extends Model
{
    protected $table = 'penerjemahan_immediate_lv3';

    protected $fillable = [
        'immediate_outcome_lv3_id',
        'unit_kerja_id',
        'tahun',
    ];

    public function immediateOutcomeLv3(): BelongsTo
    {
        return $this->belongsTo(ImmediateOutcomeLv3::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(PenerjemahanImmediateLv3Indicator::class);
    }

    public function realisasi(): HasMany
    {
        return $this->hasMany(RealisasiImmediateLv3::class);
    }
}
