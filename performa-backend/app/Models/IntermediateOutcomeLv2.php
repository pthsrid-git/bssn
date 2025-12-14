<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntermediateOutcomeLv2 extends Model
{
    protected $table = 'intermediate_outcomes_lv2';

    protected $fillable = [
        'intermediate_outcome_lv1_id',
        'kode_uo',
        'kode_int_o_lv1',
        'kode_int_o_lv2',
        'sasaran_program',
    ];

    public function intermediateOutcomeLv1(): BelongsTo
    {
        return $this->belongsTo(IntermediateOutcomeLv1::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(IntermediateOutcomeLv2Indicator::class);
    }

    public function immediateOutcomesLv3(): HasMany
    {
        return $this->hasMany(ImmediateOutcomeLv3::class);
    }

    public function penerjemahan(): HasMany
    {
        return $this->hasMany(PenerjemahanIntermediateLv2::class);
    }
}
