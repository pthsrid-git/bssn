<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerjemahanImmediateLv3Indicator extends Model
{
    protected $fillable = [
        'penerjemahan_immediate_lv3_id',
        'iksk',
    ];

    public function penerjemahan(): BelongsTo
    {
        return $this->belongsTo(PenerjemahanImmediateLv3::class);
    }

    public function realisasi(): HasMany
    {
        return $this->hasMany(RealisasiImmediateLv3::class, 'indicator_id');
    }
}
