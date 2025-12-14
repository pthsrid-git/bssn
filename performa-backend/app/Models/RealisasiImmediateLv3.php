<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisasiImmediateLv3 extends Model
{
    protected $table = 'realisasi_immediate_lv3';

    protected $fillable = [
        'penerjemahan_immediate_lv3_id',
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
        return $this->belongsTo(PenerjemahanImmediateLv3::class, 'penerjemahan_immediate_lv3_id');
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(PenerjemahanImmediateLv3Indicator::class, 'indicator_id');
    }
}
