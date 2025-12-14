<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerjemahanIntermediateLv2Indicator extends Model
{
    protected $fillable = [
        'penerjemahan_intermediate_lv2_id',
        'iksp',
    ];

    public function penerjemahan(): BelongsTo
    {
        return $this->belongsTo(PenerjemahanIntermediateLv2::class);
    }

    public function realisasi(): HasMany
    {
        return $this->hasMany(RealisasiIntermediateLv2::class, 'indicator_id');
    }
}
