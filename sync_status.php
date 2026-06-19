<?php

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->boot();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$today = Carbon::today()->toDateString();

// Berlangsung: mulai <= hari ini <= selesai
$berlangsung = DB::table('jadwal_itikafs')
    ->where('status', 'dijadwalkan')
    ->whereDate('tanggal_mulai', '<=', $today)
    ->whereDate('tanggal_selesai', '>=', $today)
    ->update(['status' => 'berlangsung']);

// Selesai: selesai < hari ini
$selesai = DB::table('jadwal_itikafs')
    ->whereIn('status', ['dijadwalkan', 'berlangsung'])
    ->whereDate('tanggal_selesai', '<', $today)
    ->update(['status' => 'selesai']);

echo "Sync selesai! Diubah ke berlangsung: $berlangsung, selesai: $selesai\n";

// Tampilkan semua jadwal
$jadwals = DB::table('jadwal_itikafs')->get(['id', 'nama_itikaf', 'tanggal_mulai', 'tanggal_selesai', 'status']);
foreach ($jadwals as $j) {
    echo "ID:{$j->id} | {$j->nama_itikaf} | {$j->tanggal_mulai} - {$j->tanggal_selesai} | STATUS: {$j->status}\n";
}
