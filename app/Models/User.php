<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telepon',
        'umur',
        'role',
        'wilayah_id',
        'asal_daerah',
        'mahallah_id',
        'foto_profil',
        'fcm_token',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function mahallah()
    {
        return $this->belongsTo(Mahallah::class, 'mahallah_id');
    }

    public function pendaftaranWajah()
    {
        return $this->hasOne(PendaftaranWajah::class, 'pengguna_id');
    }
}