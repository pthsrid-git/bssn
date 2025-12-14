<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntermediateOutcomeLv1 extends Model
{
    protected $table = 'intermediate_outcomes_lv1';

    protected $fillable = [
        'final_outcome_id',
        'kode_uo',
        'kode_int_o_lv1',
        'sasaran',
    ];

    public function finalOutcome(): BelongsTo
    {
        return $this->belongsTo(FinalOutcome::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(IntermediateOutcomeLv1Indicator::class);
    }

    public function intermediateOutcomesLv2(): HasMany
    {
        return $this->hasMany(IntermediateOutcomeLv2::class);
    }
}
