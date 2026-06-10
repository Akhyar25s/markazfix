<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanItikaf extends Model
{
    use HasFactory;

    protected $table = 'laporan_itikafs';

    protected $fillable = [
        'jadwal_itikaf_id',
        'amir_id',
        'nama_sesi',
        'waktu_mulai',
        'waktu_selesai',
        'uraian_kegiatan',
        'peserta_hadir',
        'status',
        'catatan_wilayah',
        'catatan_inti',
        'dikirim_pada',
        'disetujui_pada',
        'dokumen_pendukung',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'peserta_hadir' => 'array',
        'dokumen_pendukung' => 'array',
        'dikirim_pada' => 'datetime',
        'disetujui_pada' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalItikaf::class, 'jadwal_itikaf_id');
    }

    public function amir()
    {
        return $this->belongsTo(User::class, 'amir_id');
    }

    public function berkas()
    {
        return $this->hasMany(BerkasLaporan::class);
    }
}