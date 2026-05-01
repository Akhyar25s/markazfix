<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JadwalItikaf;
use App\Models\Wilayah;
use App\Models\PesertaItikaf;
use App\Models\AbsensiItikaf;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Total Anggota
        if ($user->role === 'pengurus_wilayah') {
            $totalAnggota = User::where('wilayah_id', $user->wilayah_id)->count();
            $jumlahWilayah = 1; // Hanya melihat wilayahnya sendiri
        } else {
            $totalAnggota = User::count();
            $jumlahWilayah = Wilayah::count();
        }

        // 2. I'tikaf Berjalan
        $itikafBerjalan = JadwalItikaf::where('status', 'berlangsung')->count();
        
        // 3. Menunggu (Dijadwalkan)
        $menungguPelaksanaan = JadwalItikaf::where('status', 'dijadwalkan')->count();

        // 4. Jadwal Mendatang
        $jadwalMendatang = JadwalItikaf::where('status', 'dijadwalkan')
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        // 5. Riwayat Kegiatan Selesai
        $riwayatKegiatan = JadwalItikaf::where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 6. Data Chart (Tren Kehadiran 5 Jadwal Terakhir)
        $jadwalUntukChart = JadwalItikaf::whereIn('status', ['selesai', 'berlangsung'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();
            
        $chartLabels = [];
        $chartHadir = [];
        $chartTidakHadir = [];
        
        foreach ($jadwalUntukChart as $jadwal) {
            $chartLabels[] = $jadwal->nama_itikaf;
            
            // Hitung total peserta yang mendaftar
            $totalPeserta = PesertaItikaf::where('jadwal_itikaf_id', $jadwal->id)->count();
            
            // Hitung unik user yang hadir
            $totalHadir = AbsensiItikaf::where('jadwal_itikaf_id', $jadwal->id)
                ->where('status_absen', 'berhasil')
                ->distinct('pengguna_id')
                ->count('pengguna_id');
                
            $chartHadir[] = $totalHadir;
            $chartTidakHadir[] = max(0, $totalPeserta - $totalHadir);
        }
        
        $persentaseHadirTerakhir = 0;
        if (count($chartHadir) > 0) {
            $lastHadir = end($chartHadir);
            $lastTidak = end($chartTidakHadir);
            $total = $lastHadir + $lastTidak;
            $persentaseHadirTerakhir = $total > 0 ? round(($lastHadir / $total) * 100) : 0;
        }

        return view('dashboard', compact(
            'totalAnggota',
            'jumlahWilayah',
            'itikafBerjalan',
            'menungguPelaksanaan',
            'jadwalMendatang',
            'riwayatKegiatan',
            'chartLabels',
            'chartHadir',
            'chartTidakHadir',
            'persentaseHadirTerakhir'
        ));
    }
}
