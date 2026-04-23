<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiItikaf extends Model
{
    use HasFactory;

    protected $table = 'absensi_itikafs';

    protected $fillable = [
        'jadwal_itikaf_id',
        'pengguna_id',
        'waktu_absen',
        'latitude_aktual',
        'longitude_aktual',
        'jarak_meter',
        'status_gps',
        'status_wajah',
        'status_absen',
        'keterangan_gagal',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalItikaf::class, 'jadwal_itikaf_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}