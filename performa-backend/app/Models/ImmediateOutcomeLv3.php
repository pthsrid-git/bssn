<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImmediateOutcomeLv3 extends Model
{
    protected $table = 'immediate_outcomes_lv3';

    protected $fillable = [
        'intermediate_outcome_lv2_id',
        'kode_uo',
        'kode_int_o_lv1',
        'kode_int_o_lv2',
        'kode_imm_o_lv3',
        'sasaran_kegiatan',
    ];

    public function intermediateOutcomeLv2(): BelongsTo
    {
        return $this->belongsTo(IntermediateOutcomeLv2::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(ImmediateOutcomeLv3Indicator::class);
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(Output::class);
    }

    public function penerjemahan(): HasMany
    {
        return $this->hasMany(PenerjemahanImmediateLv3::class);
    }
}
