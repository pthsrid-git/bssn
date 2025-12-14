<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Output extends Model
{
    protected $fillable = [
        'immediate_outcome_lv3_id',
        'kode_uo',
        'kode_int_o_lv1',
        'kode_int_o_lv2',
        'kode_imm_o_lv3',
        'kode_io',
        'deskripsi',
    ];

    public function immediateOutcomeLv3(): BelongsTo
    {
        return $this->belongsTo(ImmediateOutcomeLv3::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(OutputIndicator::class);
    }
}
