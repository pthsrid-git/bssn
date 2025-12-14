<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisasiIntermediateLv2 extends Model
{
    protected $table = 'realisasi_intermediate_lv2';

    protected $fillable = [
        'penerjemahan_intermediate_lv2_id',
        'indicator_id',
        'tahun',
        'target_tahunan',
        'realisasi_tahunan',
        'target_tw1',
        'target_tw2',
        'target_tw3',
        'target_tw4',
        'realisasi_tw1',
        'realisasi_tw2',
        'realisasi_tw3',
        'realisasi_tw4',
    ];

    protected $casts = [
        'target_tahunan' => 'decimal:2',
        'realisasi_tahunan' => 'decimal:2',
        'target_tw1' => 'decimal:2',
        'target_tw2' => 'decimal:2',
        'target_tw3' => 'decimal:2',
        'target_tw4' => 'decimal:2',
        'realisasi_tw1' => 'decimal:2',
        'realisasi_tw2' => 'decimal:2',
        'realisasi_tw3' => 'decimal:2',
        'realisasi_tw4' => 'decimal:2',
    ];

    public function penerjemahan(): BelongsTo
    {
        return $this->belongsTo(PenerjemahanIntermediateLv2::class, 'penerjemahan_intermediate_lv2_id');
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(PenerjemahanIntermediateLv2Indicator::class, 'indicator_id');
    }
}
