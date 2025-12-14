<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermediateOutcomeLv2Indicator extends Model
{
    protected $fillable = [
        'intermediate_outcome_lv2_id',
        'iksp',
    ];

    public function intermediateOutcomeLv2(): BelongsTo
    {
        return $this->belongsTo(IntermediateOutcomeLv2::class);
    }
}
