<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalItikaf;
use App\Models\LaporanItikaf;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pengurus_inti') {
            return $this->dashboardPengurusInti();
        } elseif ($user->role === 'pengurus_wilayah') {
            return $this->dashboardPengurusWilayah($user);
        } else {
            return $this->dashboardAnggota($user);
        }
    }

    private function dashboardPengurusInti()
    {
        // Statistik global
        $totalAnggota = User::where('role', 'anggota')->where('status', 'aktif')->count();
        $itikafBerjalan = JadwalItikaf::where('status', 'berlangsung')->count();
        $itikafDijadwalkan = JadwalItikaf::where('status', 'dijadwalkan')->count();

        // Laporan menunggu persetujuan Pengurus Inti
        $laporanMenunggu = LaporanItikaf::where('status', 'menunggu_inti')->count();

        // 5 laporan terbaru untuk tabel
        $laporanTerbaru = LaporanItikaf::with(['jadwal', 'amir'])
            ->whereIn('status', ['menunggu_inti', 'menunggu_wilayah', 'disetujui', 'dikembalikan_inti', 'dikembalikan_wilayah'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Jadwal mendatang
        $jadwalMendatang = JadwalItikaf::whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(3)
            ->get();

        // Data untuk peta mahallah (ditangani MahallahController)

        return view('dashboard', compact(
            'totalAnggota',
            'itikafBerjalan',
            'itikafDijadwalkan',
            'laporanMenunggu',
            'laporanTerbaru',
            'jadwalMendatang'
        ));
    }

    private function dashboardPengurusWilayah(User $user)
    {
        $wilayahId = $user->wilayah_id;

        $totalAnggota = User::where('role', 'anggota')
            ->where('status', 'aktif')
            ->where('wilayah_id', $wilayahId)
            ->count();

        $itikafBerjalan = JadwalItikaf::where('status', 'berlangsung')->count();

        // Laporan menunggu review Pengurus Wilayah (dari Amir)
        $laporanMenunggu = LaporanItikaf::where('status', 'menunggu_wilayah')
            ->whereHas('jadwal.pesertas.pengguna', function ($q) use ($wilayahId) {
                $q->where('wilayah_id', $wilayahId);
            })->count();

        // Laporan terbaru yang relevan untuk wilayah ini
        $laporanTerbaru = LaporanItikaf::with(['jadwal', 'amir'])
            ->where('status', 'menunggu_wilayah')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Jadwal mendatang
        $jadwalMendatang = JadwalItikaf::whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(3)
            ->get();

        $laporanMenungguCount = $laporanMenunggu;

        return view('dashboard', compact(
            'totalAnggota',
            'itikafBerjalan',
            'laporanTerbaru',
            'jadwalMendatang'
        ) + ['laporanMenunggu' => $laporanMenungguCount, 'itikafDijadwalkan' => 0]);
    }

    private function dashboardAnggota(User $user)
    {
        // Jadwal i'tikaf yang diikuti user ini
        $jadwalSaya = DB::table('peserta_itikafs as p')
            ->join('jadwal_itikafs as j', 'j.id', '=', 'p.jadwal_itikaf_id')
            ->where('p.pengguna_id', $user->id)
            ->orderBy('j.tanggal_mulai', 'desc')
            ->select('j.*', 'p.adalah_amir')
            ->limit(5)
            ->get();

        // Total kehadiran i'tikaf berhasil
        $totalHadir = DB::table('absensi_itikafs')
            ->where('pengguna_id', $user->id)
            ->where('status_absen', 'berhasil')
            ->count();

        // Jadwal mendatang
        $jadwalMendatang = JadwalItikaf::whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(3)
            ->get();

        $totalAnggota = 0;
        $itikafBerjalan = JadwalItikaf::where('status', 'berlangsung')->count();
        $laporanMenunggu = 0;
        $laporanTerbaru = collect();
        $itikafDijadwalkan = 0;

        return view('dashboard', compact(
            'totalAnggota',
            'itikafBerjalan',
            'laporanMenunggu',
            'laporanTerbaru',
            'jadwalMendatang',
            'jadwalSaya',
            'totalHadir',
            'itikafDijadwalkan'
        ));
    }
}
