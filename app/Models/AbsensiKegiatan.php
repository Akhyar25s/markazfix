<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'absensi_kegiatans';

    protected $fillable = [
        'pengguna_id',
        'jenis_kegiatan_id',
        'waktu_kegiatan',
        'status_wajah',
        'status_absen',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}