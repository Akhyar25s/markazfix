<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalItikaf extends Model
{
    use HasFactory;

    protected $table = 'jadwal_itikafs';

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected $fillable = [
        'nama_itikaf',
        'tanggal_mulai',
        'tanggal_selesai',
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius_meter',
        'keterangan',
        'dibuat_oleh',
        'status',
        'mahallah_id',
        'tempat_ibadah_id',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function mahallah()
    {
        return $this->belongsTo(Mahallah::class, 'mahallah_id');
    }

    public function tempatIbadah()
    {
        return $this->belongsTo(TempatIbadah::class, 'tempat_ibadah_id');
    }

    public function pesertas()
    {
        // Gunakan foreign key yang benar: jadwal_itikaf_id
        return $this->hasMany(PesertaItikaf::class, 'jadwal_itikaf_id');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanItikaf::class, 'jadwal_itikaf_id');
    }
}