<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalItikaf extends Model
{
    use HasFactory;

    protected $table = 'jadwal_itikafs';

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
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pesertas()
    {
        return $this->hasMany(PesertaItikaf::class);
    }
}