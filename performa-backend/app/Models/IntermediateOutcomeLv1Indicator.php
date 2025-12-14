<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermediateOutcomeLv1Indicator extends Model
{
    protected $fillable = [
        'intermediate_outcome_lv1_id',
        'indikator_kinerja',
    ];

    public function intermediateOutcomeLv1(): BelongsTo
    {
        return $this->belongsTo(IntermediateOutcomeLv1::class);
    }
}
