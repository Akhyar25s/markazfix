<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wilayah;
use App\Models\Mahallah;
use App\Models\JadwalItikaf;
use App\Models\PesertaItikaf;
use App\Models\AbsensiItikaf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. BUAT WILAYAH BANJARMASIN
        // ============================================================
        $wilayahs = [
            ['nama_wilayah' => 'Banjarmasin Timur',   'deskripsi' => 'Mencakup halaqoh area Timur Kota Banjarmasin', 'status' => 'aktif'],
            ['nama_wilayah' => 'Banjarmasin Utara',   'deskripsi' => 'Mencakup halaqoh area Utara Kota Banjarmasin', 'status' => 'aktif'],
            ['nama_wilayah' => 'Banjarmasin Selatan', 'deskripsi' => 'Mencakup halaqoh area Selatan Kota Banjarmasin', 'status' => 'aktif'],
            ['nama_wilayah' => 'Banjarmasin Barat',   'deskripsi' => 'Mencakup halaqoh area Barat Kota Banjarmasin', 'status' => 'aktif'],
            ['nama_wilayah' => 'Banjarmasin Tengah',  'deskripsi' => 'Mencakup halaqoh area Tengah Kota Banjarmasin', 'status' => 'aktif'],
            ['nama_wilayah' => 'Kabupaten Barito Kuala', 'deskripsi' => 'Mencakup area Marabahan, Barambai, Tamban, Anjir Wanaraya', 'status' => 'aktif'],
            ['nama_wilayah' => 'Kabupaten Kapuas',    'deskripsi' => 'Mencakup area Kapuas dan Lamunte', 'status' => 'aktif'],
        ];

        $createdWilayahs = [];
        foreach ($wilayahs as $w) {
            $createdWilayahs[] = Wilayah::create($w);
        }

        [$wTimur, $wUtara, $wSelatan, $wBarat, $wTengah, $wBatola, $wKapuas] = $createdWilayahs;

        // ============================================================
        // 2. BUAT MAHALLAH / HALAQOH BERDASARKAN DATA ASLI
        //    (dengan koordinat GPS Banjarmasin yang nyata)
        // ============================================================
        $mahallahs = [
            // --- TIMUR ---
            [
                'wilayah_id' => $wTimur->id,
                'nama_mahallah' => 'Halaqoh Timur 1',
                'alamat' => 'Jl. A. Yani Km. 2, Banjarmasin Timur',
                'latitude' => -3.3172,
                'longitude' => 114.5990,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wTimur->id,
                'nama_mahallah' => 'Halaqoh Timur 2',
                'alamat' => 'Jl. Gatot Subroto, Banjarmasin Timur',
                'latitude' => -3.3130,
                'longitude' => 114.6050,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wTimur->id,
                'nama_mahallah' => 'Halaqoh Timur 3',
                'alamat' => 'Jl. Sutoyo S., Banjarmasin Timur',
                'latitude' => -3.3200,
                'longitude' => 114.6090,
                'status' => 'aktif',
            ],
            // --- SELATAN ---
            [
                'wilayah_id' => $wSelatan->id,
                'nama_mahallah' => 'Halaqoh Selatan',
                'alamat' => 'Jl. Pekapuran Raya, Banjarmasin Selatan',
                'latitude' => -3.3420,
                'longitude' => 114.5870,
                'status' => 'aktif',
            ],
            // --- BARAT ---
            [
                'wilayah_id' => $wBarat->id,
                'nama_mahallah' => 'Halaqoh Barat',
                'alamat' => 'Jl. Belitung Darat, Banjarmasin Barat',
                'latitude' => -3.3100,
                'longitude' => 114.5620,
                'status' => 'aktif',
            ],
            // --- TENGAH ---
            [
                'wilayah_id' => $wTengah->id,
                'nama_mahallah' => 'Halaqoh Tengah',
                'alamat' => 'Jl. Veteran, Banjarmasin Tengah',
                'latitude' => -3.3250,
                'longitude' => 114.5830,
                'status' => 'aktif',
            ],
            // --- UTARA ---
            [
                'wilayah_id' => $wUtara->id,
                'nama_mahallah' => 'Halaqoh Utara 1',
                'alamat' => 'Jl. Antasari, Banjarmasin Utara',
                'latitude' => -3.2950,
                'longitude' => 114.5780,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wUtara->id,
                'nama_mahallah' => 'Halaqoh Utara 2',
                'alamat' => 'Jl. Pangeran Suriansyah, Banjarmasin Utara',
                'latitude' => -3.2870,
                'longitude' => 114.5730,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wUtara->id,
                'nama_mahallah' => 'Halaqoh Utara 3',
                'alamat' => 'Jl. Lambung Mangkurat Utara, Banjarmasin Utara',
                'latitude' => -3.2810,
                'longitude' => 114.5700,
                'status' => 'aktif',
            ],
            // --- BARITO KUALA ---
            [
                'wilayah_id' => $wBatola->id,
                'nama_mahallah' => 'Halaqoh Danda Jaya',
                'alamat' => 'Desa Danda Jaya, Kec. Rantau Badauh, Batola',
                'latitude' => -3.2700,
                'longitude' => 114.5200,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wBatola->id,
                'nama_mahallah' => 'Halaqoh Marabahan / Barambai',
                'alamat' => 'Jl. Jend. Sudirman, Marabahan, Batola',
                'latitude' => -3.0220,
                'longitude' => 114.7710,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wBatola->id,
                'nama_mahallah' => 'Halaqoh Tamban',
                'alamat' => 'Kec. Tamban, Batola',
                'latitude' => -3.4400,
                'longitude' => 114.4900,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wBatola->id,
                'nama_mahallah' => 'Halaqoh Anjir Wanaraya',
                'alamat' => 'Kec. Anjir Muara, Batola',
                'latitude' => -3.3680,
                'longitude' => 114.4540,
                'status' => 'aktif',
            ],
            // --- KAPUAS ---
            [
                'wilayah_id' => $wKapuas->id,
                'nama_mahallah' => 'Halaqoh Kapuas',
                'alamat' => 'Jl. Tambun Bungai, Kuala Kapuas',
                'latitude' => -3.0012,
                'longitude' => 114.3930,
                'status' => 'aktif',
            ],
            [
                'wilayah_id' => $wKapuas->id,
                'nama_mahallah' => 'Halaqoh Lamunte',
                'alamat' => 'Desa Lamunte, Kab. Kapuas',
                'latitude' => -3.1100,
                'longitude' => 114.3500,
                'status' => 'aktif',
            ],
        ];

        $createdMahallahs = [];
        foreach ($mahallahs as $m) {
            $createdMahallahs[] = Mahallah::create($m);
        }

        // ============================================================
        // 3. BUAT AKUN PENGURUS INTI
        // ============================================================
        $pengurusInti = User::create([
            'name' => 'Ahmad Fauzi (Pengurus Inti)',
            'email' => 'inti@markaz.com',
            'password' => Hash::make('password123'),
            'no_telepon' => '08111111111',
            'role' => 'pengurus_inti',
            'status' => 'aktif',
        ]);

        // ============================================================
        // 4. BUAT AKUN PENGURUS WILAYAH (1 per wilayah utama)
        // ============================================================
        $pengurusWilayahs = [
            [$wTimur,   'Muhammad Rizky',    'wilayah.timur@markaz.com',   '08122001001'],
            [$wUtara,   'Rahmat Hidayat',    'wilayah.utara@markaz.com',   '08122002002'],
            [$wSelatan, 'Syarif Hidayatullah','wilayah.selatan@markaz.com', '08122003003'],
            [$wBarat,   'Abdul Hamid',       'wilayah.barat@markaz.com',   '08122004004'],
            [$wTengah,  'Zainudin Akhyar',   'wilayah.tengah@markaz.com',  '08122005005'],
            [$wBatola,  'Hasanuddin',        'wilayah.batola@markaz.com',  '08122006006'],
            [$wKapuas,  'Nordin',            'wilayah.kapuas@markaz.com',  '08122007007'],
        ];

        foreach ($pengurusWilayahs as [$wilayah, $name, $email, $phone]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'no_telepon' => $phone,
                'role' => 'pengurus_wilayah',
                'wilayah_id' => $wilayah->id,
                'status' => 'aktif',
            ]);
        }

        // ============================================================
        // 5. BUAT ANGGOTA (5 orang per mahallah = 75 anggota)
        // ============================================================
        $namaAnggota = [
            'Abdullah', 'Ibrahim', 'Ismail', 'Yusuf', 'Umar',
            'Ali', 'Hasan', 'Husain', 'Bilal', 'Salman',
            'Khaled', 'Tariq', 'Zaid', 'Hamza', 'Walid',
            'Ridwan', 'Faisal', 'Naufal', 'Dzikri', 'Ilham',
        ];

        $anggotaCreated = [];
        $counter = 1;
        foreach ($createdMahallahs as $idx => $mahallah) {
            for ($i = 0; $i < 5; $i++) {
                $nama = $namaAnggota[($idx * 5 + $i) % count($namaAnggota)];
                $anggota = User::create([
                    'name' => $nama . ' ' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'email' => 'anggota' . $counter . '@markaz.com',
                    'password' => Hash::make('password123'),
                    'no_telepon' => '0812300' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                    'role' => 'anggota',
                    'wilayah_id' => $mahallah->wilayah_id,
                    'mahallah_id' => $mahallah->id,
                    'status' => 'aktif',
                ]);
                $anggotaCreated[] = $anggota;
                $counter++;
            }
        }

        // ============================================================
        // 6. BUAT JADWAL I'TIKAF (yang sudah selesai dan sedang berlangsung)
        // ============================================================
        $jadwals = [
            [
                'nama_itikaf' => "I'tikaf Awal Tahun 1446 H",
                'nama_lokasi' => 'Masjid Raya Sabilal Muhtadin, Banjarmasin',
                'tanggal_mulai' => '2026-01-10',
                'tanggal_selesai' => '2026-01-12',
                'status' => 'selesai',
                'keterangan' => 'I\'tikaf gabungan seluruh halaqoh dalam rangka awal tahun 1446 H.',
            ],
            [
                'nama_itikaf' => "I'tikaf Rajab 1446 H",
                'nama_lokasi' => 'Masjid Al-Jihad, Banjarmasin Utara',
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-02-03',
                'status' => 'selesai',
                'keterangan' => 'I\'tikaf bulanan Rajab 1446 H.',
            ],
            [
                'nama_itikaf' => "I'tikaf Pertengahan Sya'ban 1446 H",
                'nama_lokasi' => 'Masjid Nurul Islam, Banjarmasin Timur',
                'tanggal_mulai' => '2026-03-15',
                'tanggal_selesai' => '2026-03-17',
                'status' => 'selesai',
                'keterangan' => 'I\'tikaf dalam rangka menyambut bulan Ramadhan.',
            ],
            [
                'nama_itikaf' => "I'tikaf Bulanan Mei 1446 H",
                'nama_lokasi' => 'Masjid As-Salam, Banjarmasin Selatan',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(2)->toDateString(),
                'status' => 'berlangsung',
                'keterangan' => 'I\'tikaf bulanan yang sedang berlangsung.',
            ],
            [
                'nama_itikaf' => "I'tikaf Dzulhijjah 1446 H",
                'nama_lokasi' => 'Masjid Raya Sabilal Muhtadin, Banjarmasin',
                'tanggal_mulai' => now()->addDays(30)->toDateString(),
                'tanggal_selesai' => now()->addDays(32)->toDateString(),
                'status' => 'dijadwalkan',
                'keterangan' => 'I\'tikaf akbar menyambut bulan Dzulhijjah 1446 H.',
            ],
        ];

        $createdJadwals = [];
        foreach ($jadwals as $j) {
            $j['dibuat_oleh'] = $pengurusInti->id;
            $createdJadwals[] = JadwalItikaf::create($j);
        }

        // ============================================================
        // 7. BUAT DATA PESERTA & ABSENSI (untuk jadwal yang sudah selesai)
        // ============================================================
        $selesaiJadwals = array_filter($createdJadwals, fn($j) => $j->status === 'selesai');

        foreach ($selesaiJadwals as $jadwal) {
            $pesertaCount = rand(20, 35);
            $selectedAnggota = collect($anggotaCreated)->random($pesertaCount);

            foreach ($selectedAnggota as $anggota) {
                PesertaItikaf::create([
                    'jadwal_itikaf_id' => $jadwal->id,
                    'pengguna_id' => $anggota->id,
                    'adalah_amir' => false,
                ]);

                // 80% chance hadir
                if (rand(1, 10) <= 8) {
                    AbsensiItikaf::create([
                        'jadwal_itikaf_id' => $jadwal->id,
                        'pengguna_id' => $anggota->id,
                        'waktu_absen' => now()->subDays(rand(1, 60)),
                        'status_wajah' => 'dikenali',
                        'status_absen' => 'berhasil',
                    ]);
                }
            }
        }

        // Peserta untuk jadwal yang sedang berlangsung
        $berlangsung = collect($createdJadwals)->firstWhere('status', 'berlangsung');
        if ($berlangsung) {
            $selectedAnggota = collect($anggotaCreated)->random(25);
            foreach ($selectedAnggota as $anggota) {
                PesertaItikaf::create([
                    'jadwal_itikaf_id' => $berlangsung->id,
                    'pengguna_id' => $anggota->id,
                    'adalah_amir' => false,
                ]);
            }
        }

        $this->command->info('✅ Demo Seeder berhasil! Data Markaz Banjarmasin telah dimuat:');
        $this->command->info('   - ' . count($createdWilayahs) . ' Wilayah');
        $this->command->info('   - ' . count($createdMahallahs) . ' Halaqoh/Mahallah');
        $this->command->info('   - ' . count($anggotaCreated) . ' Akun Anggota');
        $this->command->info('   - ' . count($createdJadwals) . ' Jadwal I\'tikaf');
        $this->command->info('');
        $this->command->info('📧 Login sebagai Pengurus Inti: inti@markaz.com / password123');
        $this->command->info('📧 Login sebagai Pengurus Wilayah Timur: wilayah.timur@markaz.com / password123');
    }
}
