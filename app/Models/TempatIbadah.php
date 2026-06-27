<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatIbadah extends Model
{
    use HasFactory;

    protected $table = 'tempat_ibadahs';

    protected $fillable = [
        'mahallah_id',
        'nama',
        'jenis',
        'foto',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    public function mahallah()
    {
        return $this->belongsTo(Mahallah::class, 'mahallah_id');
    }

    public function jadwalItikafs()
    {
        return $this->hasMany(JadwalItikaf::class, 'tempat_ibadah_id');
    }
}
