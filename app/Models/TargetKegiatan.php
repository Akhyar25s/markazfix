<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetKegiatan extends Model
{
    use HasFactory;

    protected $table = 'target_kegiatans';

    protected $fillable = [
        'jenis_kegiatan_id',
        'jumlah_target',
        'periode',
        'tahun',
        'bulan',
        'ditetapkan_oleh',
    ];

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }

    public function penetap()
    {
        return $this->belongsTo(User::class, 'ditetapkan_oleh');
    }
}