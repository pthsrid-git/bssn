<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'tahun_mulai',
        'tahun_selesai',
        'status',
    ];

    // Tambahkan appends untuk accessor
    protected $appends = ['tahun', 'nama'];

    public function finalOutcomes(): HasMany
    {
        return $this->hasMany(FinalOutcome::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessor untuk tahun
    public function getTahunAttribute()
    {
        return $this->tahun_mulai;
    }

    // Accessor untuk nama
    public function getNamaAttribute()
    {
        if ($this->tahun_mulai == $this->tahun_selesai) {
            return "{$this->tahun_mulai}";
        }
        return "{$this->tahun_mulai} - {$this->tahun_selesai}";
    }
}
