<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasLaporan extends Model
{
    use HasFactory;

    protected $table = 'berkas_laporans';

    protected $fillable = [
        'laporan_itikaf_id',
        'nama_berkas',
        'path_s3',
        'tipe_berkas',
        'ukuran_berkas',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanItikaf::class, 'laporan_itikaf_id');
    }
}