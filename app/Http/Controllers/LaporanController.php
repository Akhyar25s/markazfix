<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JadwalItikaf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Halaman utama daftar laporan (pilih jadwal)
     */
    public function index()
    {
        $jadwals = JadwalItikaf::withCount('pesertas')
                    ->orderBy('tanggal_mulai', 'desc')
                    ->get();

        return view('laporan.index', compact('jadwals'));
    }

    /**
     * Detail laporan presensi untuk jadwal tertentu
     */
    public function show($jadwal_id)
    {
        $jadwal = JadwalItikaf::with('pembuat')->findOrFail($jadwal_id);

        $absensi = $this->getAbsensiData($jadwal_id);
        $stats   = $this->getStats($jadwal_id, $jadwal);

        return view('laporan.show', compact('jadwal', 'absensi', 'stats'));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf($jadwal_id)
    {
        $jadwal  = JadwalItikaf::with('pembuat')->findOrFail($jadwal_id);
        $absensi = $this->getAbsensiData($jadwal_id);
        $stats   = $this->getStats($jadwal_id, $jadwal);

        $pdf = Pdf::loadView('laporan.pdf', compact('jadwal', 'absensi', 'stats'))
                  ->setPaper('a4', 'portrait');

        $safeName = \Illuminate\Support\Str::slug($jadwal->nama_itikaf, '_');
        $filename = 'Laporan_Absensi_' . $safeName . '_' . now()->format('Ymd') . '.pdf';

        // Pastikan tidak ada spasi atau output kosong yang merusak file biner
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return $pdf->download($filename);
    }

    /**
     * Export laporan ke Excel (.xls format with HTML structure)
     */
    public function exportCsv($jadwal_id)
    {
        $jadwal  = JadwalItikaf::findOrFail($jadwal_id);
        $absensi = $this->getAbsensiData($jadwal_id);
        $stats   = $this->getStats($jadwal_id, $jadwal);

        $safeName = \Illuminate\Support\Str::slug($jadwal->nama_itikaf, '_');
        $filename = 'Laporan_Absensi_' . $safeName . '_' . now()->format('Ymd') . '.xls';

        // Pastikan tidak ada spasi atau output kosong yang merusak file biner
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->view('laporan.excel', compact('jadwal', 'absensi', 'stats'), 200, $headers);
    }

    // ============================================================
    // PRIVATE HELPERS (With Scoping)
    // ============================================================

    private function getAbsensiData(int $jadwal_id)
    {
        $user = Auth::user();
        $query = DB::table('absensi_itikafs as a')
            ->join('users as u', 'u.id', '=', 'a.pengguna_id')
            ->leftJoin('wilayahs as w', 'w.id', '=', 'u.wilayah_id')
            ->where('a.jadwal_itikaf_id', $jadwal_id)
            ->select(
                'u.name as pengguna_name',
                'u.email as pengguna_email',
                'w.nama_wilayah as wilayah_nama',
                'a.waktu_absen',
                'a.jarak_meter',
                'a.status_gps',
                'a.status_wajah',
                'a.status_absen',
                'a.latitude_aktual',
                'a.longitude_aktual',
            );

        // SCOPE: Filter data jika user adalah Pengurus Wilayah
        if ($user->role === 'pengurus_wilayah') {
            $query->where('u.wilayah_id', $user->wilayah_id);
        }

        return $query->orderBy('a.waktu_absen', 'asc')->get();
    }

    private function getStats(int $jadwal_id, JadwalItikaf $jadwal): array
    {
        $user = Auth::user();
        
        $pesertaQuery = DB::table('peserta_itikafs as p')
                          ->join('users as u', 'u.id', '=', 'p.pengguna_id')
                          ->where('p.jadwal_itikaf_id', $jadwal_id);

        $absensiQuery = DB::table('absensi_itikafs as a')
                          ->join('users as u', 'u.id', '=', 'a.pengguna_id')
                          ->where('a.jadwal_itikaf_id', $jadwal_id);

        // SCOPE: Filter statistik jika user adalah Pengurus Wilayah
        if ($user->role === 'pengurus_wilayah') {
            $pesertaQuery->where('u.wilayah_id', $user->wilayah_id);
            $absensiQuery->where('u.wilayah_id', $user->wilayah_id);
        }

        $totalPeserta = $pesertaQuery->count();
        $totalHadir   = (clone $absensiQuery)->where('a.status_absen', 'berhasil')->distinct('a.pengguna_id')->count();
        $totalGagal   = (clone $absensiQuery)->where('a.status_absen', 'gagal')->count();
        
        $pctKehadiran = $totalPeserta > 0 ? round(($totalHadir / $totalPeserta) * 100, 1) : 0;

        return [
            'total_peserta'     => $totalPeserta,
            'total_hadir'       => $totalHadir,
            'total_gagal'       => $totalGagal,
            'pct_kehadiran'     => $pctKehadiran,
            'total_tidak_hadir' => max(0, $totalPeserta - $totalHadir),
        ];
    }
}
