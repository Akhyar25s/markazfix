<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahallah extends Model
{
    use HasFactory;

    protected $table = 'mahallahs';

    protected $fillable = [
        'nama_mahallah',
        'alamat',
        'foto',
        'latitude',
        'longitude',
        'wilayah_id',
        'status',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tempatIbadahs()
    {
        return $this->hasMany(TempatIbadah::class, 'mahallah_id');
    }
}