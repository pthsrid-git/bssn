<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerjemahanIntermediateLv2 extends Model
{
    protected $table = 'penerjemahan_intermediate_lv2';

    protected $fillable = [
        'intermediate_outcome_lv2_id',
        'unit_kerja_id',
        'tahun',
    ];

    public function intermediateOutcomeLv2(): BelongsTo
    {
        return $this->belongsTo(IntermediateOutcomeLv2::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(PenerjemahanIntermediateLv2Indicator::class);
    }

    public function realisasi(): HasMany
    {
        return $this->hasMany(RealisasiIntermediateLv2::class);
    }
}
