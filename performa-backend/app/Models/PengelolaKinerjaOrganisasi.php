<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengelolaKinerjaOrganisasi extends Model
{
    protected $table = 'pengelola_kinerja_organisasi';

    protected $fillable = [
        'user_id',
        'unit_kerja_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function penanggungJawabMengisiKinerja(): HasMany
    {
        return $this->hasMany(PenanggungJawabMengisiKinerja::class, 'pko_id');
    }
}
