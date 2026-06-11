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
        // 1. Buat Wilayah
        $wilayah1 = \App\Models\Wilayah::create([
            'nama_wilayah' => 'Markaz Banjarmasin',
            'deskripsi' => 'Wilayah pusat Markaz Banjarmasin',
        ]);

        // 2. Buat Mahallah (Halaqoh berdasarkan data real)
        $daftarHalaqoh = [
            'Timur 1', 'Timur 2', 'Timur 3', 
            'Selatan', 'Barat', 'Tengah', 
            'Utara 1', 'Utara 2', 'Utara 3', 
            'Danda Jaya', 'Marabahan/Barambai', 
            'Tamban', 'Kapuas', 'Lamunte', 'Anjir Wanaraya'
        ];

        $mahallahIds = [];
        foreach ($daftarHalaqoh as $halaqoh) {
            $mahallah = \App\Models\Mahallah::create([
                'wilayah_id' => $wilayah1->id,
                'nama_mahallah' => $halaqoh,
                'alamat' => 'Alamat ' . $halaqoh, // Bisa disesuaikan nanti
            ]);
            $mahallahIds[] = $mahallah->id;
        }

        // Simpan id mahallah pertama untuk dipakai di seeder user
        $mahallah1Id = $mahallahIds[0];

        // 3. Buat Akun Pengurus Inti
        $pengurusInti = User::create([
            'name' => 'Bapak Pengurus Inti',
            'email' => 'inti@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08111111111',
            'role' => 'pengurus_inti',
            'status' => 'aktif',
        ]);

        // 4. Buat Akun Pengurus Wilayah (Ditempatkan di Wilayah 1)
        $pengurusWilayah = User::create([
            'name' => 'Bapak Pengurus Wilayah',
            'email' => 'wilayah@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08222222222',
            'role' => 'pengurus_wilayah',
            'wilayah_id' => $wilayah1->id,
            'status' => 'aktif',
        ]);

        // 5. Buat Akun Anggota
        User::create([
            'name' => 'Umar Anggota',
            'email' => 'umar@markaz.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'no_telepon' => '08333333333',
            'role' => 'anggota',
            'wilayah_id' => $wilayah1->id,
            'mahallah_id' => $mahallah1Id,
            'status' => 'aktif',
        ]);

        // 6. Buat Master Data Jenis Kegiatan & Target Kegiatan (Data Real)
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

            // Set Target untuk Bulan Februari 2026 (sesuai foto)
            \App\Models\TargetKegiatan::create([
                'jenis_kegiatan_id' => $jenis->id,
                'jumlah_target' => $keg['target'],
                'periode' => 'bulanan',
                'tahun' => 2026,
                'bulan' => 2, // Februari
                'ditetapkan_oleh' => $pengurusInti->id, // Menggunakan ID pengurus inti
            ]);
        }

        // 7. Buat Jadwal I'tikaf (Bulan Februari 2026)
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

        // 8. Daftarkan Anggota sebagai Amir dan Peserta
        // Gunakan pengurusWilayah sebagai Amir
        \App\Models\PesertaItikaf::create([
            'jadwal_itikaf_id' => $jadwal->id,
            'pengguna_id'      => $pengurusWilayah->id,
            'adalah_amir'      => true,
            'dipilih_oleh'     => $pengurusInti->id,
        ]);

        // 9. Buat 4 Laporan Sesi — Status DISETUJUI (sesuai foto 4 minggu di Februari)
        $sesiLaporan = [
            [
                'minggu'          => 'Minggu I',
                'waktu_mulai'     => '2026-02-01 08:00:00',
                'waktu_selesai'   => '2026-02-07 22:00:00',
                'kunjungan_guru'  => 8,
                'kunjungan_pelmas'=> 8,
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu I Februari 2026. Kunjungan Guru: 8, Kunjungan Pelmas: 8.',
            ],
            [
                'minggu'          => 'Minggu II',
                'waktu_mulai'     => '2026-02-08 08:00:00',
                'waktu_selesai'   => '2026-02-14 22:00:00',
                'kunjungan_guru'  => 13,
                'kunjungan_pelmas'=> 8,
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu II Februari 2026. Kunjungan Guru: 13, Kunjungan Pelmas: 8.',
            ],
            [
                'minggu'          => 'Minggu III',
                'waktu_mulai'     => '2026-02-15 08:00:00',
                'waktu_selesai'   => '2026-02-21 22:00:00',
                'kunjungan_guru'  => 13,
                'kunjungan_pelmas'=> 0,
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu III Februari 2026. Kunjungan Guru: 13.',
            ],
            [
                'minggu'          => 'Minggu IV',
                'waktu_mulai'     => '2026-02-22 08:00:00',
                'waktu_selesai'   => '2026-02-28 22:00:00',
                'kunjungan_guru'  => 0,
                'kunjungan_pelmas'=> 4,
                'uraian'          => 'Laporan pelaksanaan kegiatan I\'tikaf Minggu IV Februari 2026. Kunjungan Pelmas: 4.',
            ],
        ];

        foreach ($sesiLaporan as $sesi) {
            \App\Models\LaporanItikaf::create([
                'jadwal_itikaf_id'  => $jadwal->id,
                'amir_id'           => $pengurusWilayah->id,
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
