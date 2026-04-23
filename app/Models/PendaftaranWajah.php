<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranWajah extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_wajahs';

    protected $fillable = [
        'pengguna_id',
        'aws_face_id',
        'aws_collection_id',
        'status',
        'terdaftar_pada',
    ];

    protected $casts = [
        'terdaftar_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}