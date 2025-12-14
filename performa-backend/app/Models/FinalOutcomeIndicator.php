<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalOutcomeIndicator extends Model
{
    protected $fillable = [
        'final_outcome_id',
        'indikator_kinerja',
    ];

    public function finalOutcome(): BelongsTo
    {
        return $this->belongsTo(FinalOutcome::class);
    }
}
