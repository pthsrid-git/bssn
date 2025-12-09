<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'rencana_hasil_kinerja_skp',
        'indikator_hasil_rencana_kerja',
        'aktivitas_kegiatan_harian',
        'keterangan',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'catatan_katim',
        'catatan_atasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Valid status values
    public const STATUS_DISUBMIT = 'Disubmit';
    public const STATUS_DISETUJUI = 'Disetujui';
    public const STATUS_DITOLAK = 'Ditolak';

    public const VALID_STATUSES = [
        self::STATUS_DISUBMIT,
        self::STATUS_DISETUJUI,
        self::STATUS_DITOLAK,
    ];

    // Boot method untuk validasi
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($logbook) {
            if (!in_array($logbook->status, self::VALID_STATUSES)) {
                throw new \InvalidArgumentException(
                    'Status must be one of: ' . implode(', ', self::VALID_STATUSES)
                );
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for file URL
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return url('storage/' . $this->file_path);
        }
        return null;
    }

    public function isSubmitted()
    {
        return $this->status === self::STATUS_DISUBMIT;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}
