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

        // Total anggota per wilayah (untuk grafik/tabel)
        $anggotaPerWilayah = DB::table('users as u')
            ->join('mahallahs as m', 'm.id', '=', 'u.wilayah_id')
            ->where('u.role', 'anggota')
            ->select('m.nama_mahallah', DB::raw('count(u.id) as total'))
            ->groupBy('m.nama_mahallah')
            ->get();

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
            
        // Rekap Kegiatan Individual Global (Bulan ini)
        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');
        
        $kegiatanGlobal = DB::table('absensi_kegiatans as ak')
            ->join('jenis_kegiatans as jk', 'jk.id', '=', 'ak.jenis_kegiatan_id')
            ->whereYear('ak.waktu_kegiatan', $tahunSekarang)
            ->whereMonth('ak.waktu_kegiatan', $bulanSekarang)
            ->where('ak.status_absen', 'berhasil')
            ->select('jk.nama_kegiatan', DB::raw('count(ak.id) as total'))
            ->groupBy('jk.nama_kegiatan')
            ->get();

        return view('dashboard', compact(
            'totalAnggota',
            'itikafBerjalan',
            'itikafDijadwalkan',
            'laporanMenunggu',
            'laporanTerbaru',
            'jadwalMendatang',
            'anggotaPerWilayah',
            'kegiatanGlobal'
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

        // Jumlah peserta i'tikaf dari wilayahnya
        $totalPesertaItikaf = DB::table('peserta_itikafs as p')
            ->join('users as u', 'u.id', '=', 'p.pengguna_id')
            ->where('u.wilayah_id', $wilayahId)
            ->count();

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

        // Rekap Kegiatan Individual Wilayah (Bulan ini)
        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');
        
        $kegiatanWilayah = DB::table('absensi_kegiatans as ak')
            ->join('jenis_kegiatans as jk', 'jk.id', '=', 'ak.jenis_kegiatan_id')
            ->join('users as u', 'u.id', '=', 'ak.pengguna_id')
            ->where('u.wilayah_id', $wilayahId)
            ->whereYear('ak.waktu_kegiatan', $tahunSekarang)
            ->whereMonth('ak.waktu_kegiatan', $bulanSekarang)
            ->where('ak.status_absen', 'berhasil')
            ->select('jk.nama_kegiatan', DB::raw('count(ak.id) as total'))
            ->groupBy('jk.nama_kegiatan')
            ->get();

        $laporanMenungguCount = $laporanMenunggu;

        return view('dashboard', compact(
            'totalAnggota',
            'itikafBerjalan',
            'laporanTerbaru',
            'jadwalMendatang',
            'totalPesertaItikaf',
            'kegiatanWilayah'
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

        // Ambil progress kegiatan harian
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');

        $targets = \App\Models\TargetKegiatan::with('jenisKegiatan')
            ->where('tahun', $tahunSekarang)
            ->where(function($q) use ($bulanSekarang) {
                $q->where('periode', 'tahunan')
                  ->orWhere(function($sq) use ($bulanSekarang) {
                      $sq->where('periode', 'bulanan')->where('bulan', $bulanSekarang);
                  });
            })
            ->get();

        $progressKegiatan = [];
        foreach ($targets as $target) {
            $query = \App\Models\AbsensiKegiatan::where('pengguna_id', $user->id)
                ->where('jenis_kegiatan_id', $target->jenis_kegiatan_id)
                ->where('status_absen', 'berhasil')
                ->whereYear('waktu_kegiatan', $tahunSekarang);
                
            if ($target->periode === 'bulanan') {
                $query->whereMonth('waktu_kegiatan', $bulanSekarang);
            }

            $capaian = $query->count();
            
            $progressKegiatan[] = [
                'nama' => $target->jenisKegiatan->nama_kegiatan,
                'target' => $target->jumlah_target,
                'capaian' => $capaian,
                'persentase' => min(100, round(($capaian / max(1, $target->jumlah_target)) * 100))
            ];
        }

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
            'itikafDijadwalkan',
            'progressKegiatan'
        ));
    }
}
