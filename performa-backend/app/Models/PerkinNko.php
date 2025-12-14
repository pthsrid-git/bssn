<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerkinNko extends Model
{
    protected $table = 'perkin_nko';

    protected $fillable = [
        'unit_kerja_id',
        'tahun',
        'perkin_file',
        'nko_tw1_file',
        'nko_tw2_file',
        'nko_tw3_file',
        'nko_tw4_file',
        'nko_tahunan_file',
    ];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }
}
