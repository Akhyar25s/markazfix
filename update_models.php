<?php

$models = [
    'Wilayah' => <<<'PHP'
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
}
PHP,

    'Mahallah' => <<<'PHP'
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
        'latitude',
        'longitude',
        'wilayah_id',
        'status',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }
}
PHP,

    'JadwalItikaf' => <<<'PHP'
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
PHP,

    'PesertaItikaf' => <<<'PHP'
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
PHP,

    'AbsensiItikaf' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiItikaf extends Model
{
    use HasFactory;

    protected $table = 'absensi_itikafs';

    protected $fillable = [
        'jadwal_itikaf_id',
        'pengguna_id',
        'waktu_absen',
        'latitude_aktual',
        'longitude_aktual',
        'jarak_meter',
        'status_gps',
        'status_wajah',
        'status_absen',
        'keterangan_gagal',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalItikaf::class, 'jadwal_itikaf_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
PHP,

    'LaporanItikaf' => <<<'PHP'
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
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'peserta_hadir' => 'array',
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
PHP,

    'BerkasLaporan' => <<<'PHP'
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
PHP,

    'JenisKegiatan' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jenis_kegiatans';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'status',
    ];
}
PHP,

    'TargetKegiatan' => <<<'PHP'
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
PHP,

    'AbsensiKegiatan' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'absensi_kegiatans';

    protected $fillable = [
        'pengguna_id',
        'jenis_kegiatan_id',
        'waktu_kegiatan',
        'status_wajah',
        'status_absen',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}
PHP,

    'Notifikasi' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';

    protected $fillable = [
        'pengguna_id',
        'judul',
        'pesan',
        'tipe',
        'referensi_id',
        'referensi_tipe',
        'dibaca',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'dibaca_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
PHP,

    'PendaftaranWajah' => <<<'PHP'
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
PHP,

    'User' => <<<'PHP'
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
        'jenis_kelamin',
        'tanggal_lahir',
        'role',
        'wilayah_id',
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
PHP,
];

foreach ($models as $name => $content) {
    file_put_contents(__DIR__ . "/app/Models/{$name}.php", $content);
    echo "Updated $name\\n";
}

?>
