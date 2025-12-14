<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmediateOutcomeLv3Indicator extends Model
{
    protected $fillable = [
        'immediate_outcome_lv3_id',
        'iksk',
    ];

    public function immediateOutcomeLv3(): BelongsTo
    {
        return $this->belongsTo(ImmediateOutcomeLv3::class);
    }
}
