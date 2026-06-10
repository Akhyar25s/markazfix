<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AbsensiItikaf;
use App\Models\User;
use App\Models\JadwalItikaf;

$jadwal = JadwalItikaf::first();
$user = User::where('role', 'anggota')->first();

if ($jadwal && $user) {
    AbsensiItikaf::create([
        'jadwal_itikaf_id' => $jadwal->id,
        'pengguna_id' => $user->id,
        'waktu_absen' => now(),
        'status_absen' => 'berhasil',
        'status_wajah' => 'dikenali',
        'status_gps' => 'valid',
        'jarak_meter' => 15.5,
        'latitude_aktual' => $jadwal->latitude ?? 0,
        'longitude_aktual' => $jadwal->longitude ?? 0,
    ]);
    echo 'Mock attendance created!';
} else {
    echo 'No jadwal or user found!';
}
