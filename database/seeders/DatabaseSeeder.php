<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================================
        // 1. BUAT SEMUA WILAYAH KEPENGURUSAN
        // ============================================================
        $dataWilayah = [
            [
                'nama' => 'Banjarmasin Timur',
                'deskripsi' => 'Wilayah kepengurusan Banjarmasin Timur',
                'pengurus_nama' => 'Pengurus Wilayah Timur',
                'pengurus_email' => 'timur@markaz.com',
                'pengurus_telepon' => '08210000001',
                'mahallah' => [
                    ['nama' => 'Timur 1', 'alamat' => 'Jl. Pramuka, Banjarmasin Timur', 'lat' => -3.3244, 'lng' => 114.6214],
                    ['nama' => 'Timur 2', 'alamat' => 'Jl. Karang Mekar, Banjarmasin Timur', 'lat' => -3.3280, 'lng' => 114.6250],
                    ['nama' => 'Timur 3', 'alamat' => 'Jl. Sungai Lulut, Banjarmasin Timur', 'lat' => -3.3300, 'lng' => 114.6300],
                ],
                'anggota' => [
                    ['nama' => 'Umar Anggota Timur', 'email' => 'umar@markaz.com', 'telepon' => '08310000001'],
                ],
            ],
            [
                'nama' => 'Banjarmasin Selatan',
                'deskripsi' => 'Wilayah kepengurusan Banjarmasin Selatan',
                'pengurus_nama' => 'Pengurus Wilayah Selatan',
                'pengurus_email' => 'selatan@markaz.com',
                'pengurus_telepon' => '08210000002',
                'mahallah' => [
                    ['nama' => 'Selatan', 'alamat' => 'Jl. Kelayan, Banjarmasin Selatan', 'lat' => -3.3400, 'lng' => 114.5920],
                ],
                'anggota' => [
                    ['nama' => 'Ahmad Anggota Selatan', 'email' => 'ahmad@markaz.com', 'telepon' => '08310000002'],
                ],
            ],
            [
                'nama' => 'Banjarmasin Barat',
                'deskripsi' => 'Wilayah kepengurusan Banjarmasin Barat',
                'pengurus_nama' => 'Pengurus Wilayah Barat',
                'pengurus_email' => 'barat@markaz.com',
                'pengurus_telepon' => '08210000003',
                'mahallah' => [
                    ['nama' => 'Barat', 'alamat' => 'Jl. RE Martadinata, Banjarmasin Barat', 'lat' => -3.3150, 'lng' => 114.5750],
                ],
                'anggota' => [
                    ['nama' => 'Ali Anggota Barat', 'email' => 'ali@markaz.com', 'telepon' => '08310000003'],
                ],
            ],
            [
                'nama' => 'Banjarmasin Tengah',
                'deskripsi' => 'Wilayah kepengurusan Banjarmasin Tengah',
                'pengurus_nama' => 'Pengurus Wilayah Tengah',
                'pengurus_email' => 'tengah@markaz.com',
                'pengurus_telepon' => '08210000004',
                'mahallah' => [
                    ['nama' => 'Tengah', 'alamat' => 'Jl. Sudimampir, Banjarmasin Tengah', 'lat' => -3.3194, 'lng' => 114.5908],
                ],
                'anggota' => [
                    ['nama' => 'Hasan Anggota Tengah', 'email' => 'hasan@markaz.com', 'telepon' => '08310000004'],
                ],
            ],
            [
                'nama' => 'Banjarmasin Utara',
                'deskripsi' => 'Wilayah kepengurusan Banjarmasin Utara',
                'pengurus_nama' => 'Pengurus Wilayah Utara',
                'pengurus_email' => 'utara@markaz.com',
                'pengurus_telepon' => '08210000005',
                'mahallah' => [
                    ['nama' => 'Utara 1', 'alamat' => 'Jl. Kayutangi, Banjarmasin Utara', 'lat' => -3.2950, 'lng' => 114.5850],
                    ['nama' => 'Utara 2', 'alamat' => 'Jl. Alalak, Banjarmasin Utara', 'lat' => -3.2900, 'lng' => 114.5800],
                    ['nama' => 'Utara 3', 'alamat' => 'Jl. Sungai Jingah, Banjarmasin Utara', 'lat' => -3.2870, 'lng' => 114.5900],
                ],
                'anggota' => [
                    ['nama' => 'Ibrahim Anggota Utara', 'email' => 'ibrahim@markaz.com', 'telepon' => '08310000005'],
                ],
            ],
            [
                'nama' => 'Danda Jaya',
                'deskripsi' => 'Wilayah kepengurusan Danda Jaya',
                'pengurus_nama' => 'Pengurus Wilayah Danda Jaya',
                'pengurus_email' => 'dandajaya@markaz.com',
                'pengurus_telepon' => '08210000006',
                'mahallah' => [
                    ['nama' => 'Danda Jaya', 'alamat' => 'Danda Jaya, Barito Kuala', 'lat' => -3.3600, 'lng' => 114.5700],
                ],
                'anggota' => [
                    ['nama' => 'Yusuf Anggota Danda Jaya', 'email' => 'yusuf@markaz.com', 'telepon' => '08310000006'],
                ],
            ],
            [
                'nama' => 'Marabahan/Barambai',
                'deskripsi' => 'Wilayah kepengurusan Marabahan dan Barambai',
                'pengurus_nama' => 'Pengurus Wilayah Marabahan',
                'pengurus_email' => 'marabahan@markaz.com',
                'pengurus_telepon' => '08210000007',
                'mahallah' => [
                    ['nama' => 'Marabahan/Barambai', 'alamat' => 'Kec. Marabahan, Barito Kuala', 'lat' => -2.9830, 'lng' => 114.7650],
                ],
                'anggota' => [
                    ['nama' => 'Salman Anggota Marabahan', 'email' => 'salman@markaz.com', 'telepon' => '08310000007'],
                ],
            ],
            [
                'nama' => 'Tamban',
                'deskripsi' => 'Wilayah kepengurusan Tamban',
                'pengurus_nama' => 'Pengurus Wilayah Tamban',
                'pengurus_email' => 'tamban@markaz.com',
                'pengurus_telepon' => '08210000008',
                'mahallah' => [
                    ['nama' => 'Tamban', 'alamat' => 'Kec. Tamban, Barito Kuala', 'lat' => -3.2200, 'lng' => 114.5500],
                ],
                'anggota' => [
                    ['nama' => 'Ridwan Anggota Tamban', 'email' => 'ridwan@markaz.com', 'telepon' => '08310000008'],
                ],
            ],
            [
                'nama' => 'Kapuas',
                'deskripsi' => 'Wilayah kepengurusan Kapuas',
                'pengurus_nama' => 'Pengurus Wilayah Kapuas',
                'pengurus_email' => 'kapuas@markaz.com',
                'pengurus_telepon' => '08210000009',
                'mahallah' => [
                    ['nama' => 'Kapuas', 'alamat' => 'Kuala Kapuas, Kalimantan Tengah', 'lat' => -3.0970, 'lng' => 114.3880],
                ],
                'anggota' => [
                    ['nama' => 'Bilal Anggota Kapuas', 'email' => 'bilal@markaz.com', 'telepon' => '08310000009'],
                ],
            ],
            [
                'nama' => 'Lamunte',
                'deskripsi' => 'Wilayah kepengurusan Lamunte',
                'pengurus_nama' => 'Pengurus Wilayah Lamunte',
                'pengurus_email' => 'lamunte@markaz.com',
                'pengurus_telepon' => '08210000010',
                'mahallah' => [
                    ['nama' => 'Lamunte', 'alamat' => 'Desa Lamunte, Barito Kuala', 'lat' => -3.0500, 'lng' => 114.4200],
                ],
                'anggota' => [
                    ['nama' => 'Zaid Anggota Lamunte', 'email' => 'zaid@markaz.com', 'telepon' => '08310000010'],
                ],
            ],
            [
                'nama' => 'Anjir Wanaraya',
                'deskripsi' => 'Wilayah kepengurusan Anjir Wanaraya',
                'pengurus_nama' => 'Pengurus Wilayah Anjir Wanaraya',
                'pengurus_email' => 'anjir@markaz.com',
                'pengurus_telepon' => '08210000011',
                'mahallah' => [
                    ['nama' => 'Anjir Wanaraya', 'alamat' => 'Anjir Pasar, Barito Kuala', 'lat' => -3.0400, 'lng' => 114.6500],
                ],
                'anggota' => [
                    ['nama' => 'Khalid Anggota Anjir', 'email' => 'khalid@markaz.com', 'telepon' => '08310000011'],
                ],
            ],
        ];

        // ============================================================
        // 2. BUAT AKUN PENGURUS INTI
        // ============================================================
        $pengurusInti = User::create([
            'name' => 'Bapak Pengurus Inti',
            'email' => 'inti@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08111111111',
            'role' => 'pengurus_inti',
            'status' => 'aktif',
        ]);

        // ============================================================
        // 3. LOOP: BUAT WILAYAH, MAHALLAH, PENGURUS, & ANGGOTA
        // ============================================================
        $firstPengurus = null;

        foreach ($dataWilayah as $wData) {
            // Buat Wilayah
            $wilayah = \App\Models\Wilayah::create([
                'nama_wilayah' => $wData['nama'],
                'deskripsi' => $wData['deskripsi'],
            ]);

            // Buat Mahallah di wilayah ini
            $mahallahIds = [];
            foreach ($wData['mahallah'] as $mData) {
                $mahallah = \App\Models\Mahallah::create([
                    'wilayah_id' => $wilayah->id,
                    'nama_mahallah' => $mData['nama'],
                    'alamat' => $mData['alamat'],
                    'latitude' => $mData['lat'],
                    'longitude' => $mData['lng'],
                ]);
                $mahallahIds[] = $mahallah->id;
            }

            // Buat Pengurus Wilayah
            $pengurus = User::create([
                'name' => $wData['pengurus_nama'],
                'email' => $wData['pengurus_email'],
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'no_telepon' => $wData['pengurus_telepon'],
                'role' => 'pengurus_wilayah',
                'wilayah_id' => $wilayah->id,
                'status' => 'aktif',
            ]);
            $wilayah->update(['pengurus_id' => $pengurus->id]);

            // Simpan pengurus pertama untuk amir jadwal nanti
            if (!$firstPengurus) {
                $firstPengurus = $pengurus;
            }

            // Buat Anggota di wilayah ini
            foreach ($wData['anggota'] as $aData) {
                User::create([
                    'name' => $aData['nama'],
                    'email' => $aData['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'no_telepon' => $aData['telepon'],
                    'role' => 'anggota',
                    'wilayah_id' => $wilayah->id,
                    'mahallah_id' => $mahallahIds[0],
                    'status' => 'aktif',
                ]);
            }
        }

        // ============================================================
        // 4. BUAT MASTER DATA JENIS KEGIATAN & TARGET
        // ============================================================
        $dataKegiatan = [
            ['nama' => 'Kunjungan Guru', 'target' => 76],
            ['nama' => 'Kunjungan Pelmas', 'target' => 136],
            ['nama' => 'Duduk Ta\'lim Masjid', 'target' => 344],
            ['nama' => 'Hadir Malam Markaz', 'target' => 266],
            ['nama' => 'Keluar 1 - 3 H', 'target' => 60],
            ['nama' => 'Khuruj', 'target' => 6],
        ];

        foreach ($dataKegiatan as $keg) {
            $jenis = \App\Models\JenisKegiatan::create([
                'nama_kegiatan' => $keg['nama'],
                'deskripsi' => 'Kegiatan ' . $keg['nama'] . ' mingguan',
            ]);

            \App\Models\TargetKegiatan::create([
                'jenis_kegiatan_id' => $jenis->id,
                'jumlah_target' => $keg['target'],
                'periode' => 'bulanan',
                'tahun' => 2026,
                'bulan' => 2,
                'ditetapkan_oleh' => $pengurusInti->id,
            ]);
        }

        // ============================================================
        // 5. BUAT JADWAL I'TIKAF
        // ============================================================
        $jadwal = \App\Models\JadwalItikaf::create([
            'nama_itikaf'      => 'I\'tikaf Mingguan Februari 2026',
            'tanggal_mulai'    => '2026-02-01',
            'tanggal_selesai'  => '2026-02-28',
            'nama_lokasi'      => 'Markaz Banjarmasin',
            'latitude'         => -3.3194,
            'longitude'        => 114.5908,
            'radius_meter'     => 100,
            'keterangan'       => 'Evaluasi Perkembangan Kerja Pelmas Mingguan Februari 2026',
            'dibuat_oleh'      => $pengurusInti->id,
            'status'           => 'selesai',
        ]);

        // Daftarkan pengurus pertama (Timur) sebagai Amir
        \App\Models\PesertaItikaf::create([
            'jadwal_itikaf_id' => $jadwal->id,
            'pengguna_id'      => $firstPengurus->id,
            'adalah_amir'      => true,
            'dipilih_oleh'     => $pengurusInti->id,
        ]);

        // ============================================================
        // 6. BUAT 4 LAPORAN SESI — STATUS DISETUJUI
        // ============================================================
        $sesiLaporan = [
            [
                'minggu'          => 'Minggu I',
                'waktu_mulai'     => '2026-02-01 08:00:00',
                'waktu_selesai'   => '2026-02-07 22:00:00',
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu I Februari 2026. Kunjungan Guru: 8, Kunjungan Pelmas: 8.',
            ],
            [
                'minggu'          => 'Minggu II',
                'waktu_mulai'     => '2026-02-08 08:00:00',
                'waktu_selesai'   => '2026-02-14 22:00:00',
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu II Februari 2026. Kunjungan Guru: 13, Kunjungan Pelmas: 8.',
            ],
            [
                'minggu'          => 'Minggu III',
                'waktu_mulai'     => '2026-02-15 08:00:00',
                'waktu_selesai'   => '2026-02-21 22:00:00',
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu III Februari 2026. Kunjungan Guru: 13.',
            ],
            [
                'minggu'          => 'Minggu IV',
                'waktu_mulai'     => '2026-02-22 08:00:00',
                'waktu_selesai'   => '2026-02-28 22:00:00',
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu IV Februari 2026. Kunjungan Pelmas: 4.',
            ],
        ];

        foreach ($sesiLaporan as $sesi) {
            \App\Models\LaporanItikaf::create([
                'jadwal_itikaf_id'  => $jadwal->id,
                'amir_id'           => $firstPengurus->id,
                'nama_sesi'         => 'Laporan Sesi ' . $sesi['minggu'] . ' Februari 2026',
                'waktu_mulai'       => $sesi['waktu_mulai'],
                'waktu_selesai'     => $sesi['waktu_selesai'],
                'uraian_kegiatan'   => $sesi['uraian'],
                'peserta_hadir'     => json_encode([]),
                'status'            => 'disetujui',
                'catatan_wilayah'   => 'Laporan ' . $sesi['minggu'] . ' sudah diperiksa dan disetujui oleh Pengurus Wilayah.',
                'catatan_inti'      => 'Laporan ' . $sesi['minggu'] . ' sudah diperiksa dan disetujui oleh Pengurus Inti.',
                'dikirim_pada'      => now()->subDays(7),
                'disetujui_pada'    => now()->subDays(5),
            ]);
        }
    }
}
