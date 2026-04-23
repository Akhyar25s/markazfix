<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaItikaf extends Model
{
    use HasFactory;

    protected $table = 'peserta_itikafs';

    protected $fillable = [
        'jadwal_itikaf_id',
        'pengguna_id',
        'adalah_amir',
        'dipilih_oleh',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalItikaf::class, 'jadwal_itikaf_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function pemilih()
    {
        return $this->belongsTo(User::class, 'dipilih_oleh');
    }
}