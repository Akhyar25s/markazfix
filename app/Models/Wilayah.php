<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayahs';

    protected $fillable = [
        'nama_wilayah',
        'deskripsi',
        'pengurus_id',
        'status',
    ];

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    public function mahallahs()
    {
        return $this->hasMany(Mahallah::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}