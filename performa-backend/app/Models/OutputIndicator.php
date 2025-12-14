<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutputIndicator extends Model
{
    protected $fillable = [
        'output_id',
        'indikator_output',
    ];

    public function output(): BelongsTo
    {
        return $this->belongsTo(Output::class);
    }
}
