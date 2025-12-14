<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenanggungJawabMengisiKinerja extends Model
{
    protected $table = 'penanggung_jawab_mengisi_kinerja';

    protected $fillable = [
        'user_id',
        'unit_kerja_id',
        'pko_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function pengelolaKinerjaOrganisasi(): BelongsTo
    {
        return $this->belongsTo(PengelolaKinerjaOrganisasi::class, 'pko_id');
    }
}
