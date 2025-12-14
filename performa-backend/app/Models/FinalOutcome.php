<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalOutcome extends Model
{
    protected $fillable = [
        'periode_id',
        'kode_uo',
        'sasaran',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(FinalOutcomeIndicator::class);
    }

    public function intermediateOutcomesLv1(): HasMany
    {
        return $this->hasMany(IntermediateOutcomeLv1::class);
    }
}
