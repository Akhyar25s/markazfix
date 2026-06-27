<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use App\Services\FaceRecognitionService;

class AbsensiKegiatanController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Ambil semua target yang aktif saat ini (contoh sederhana: tahun berjalan dan bulan berjalan)
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

        // Hitung progres untuk masing-masing target
        $progress = [];
        foreach ($targets as $target) {
            $query = \App\Models\AbsensiKegiatan::where('pengguna_id', $user->id)
                ->where('jenis_kegiatan_id', $target->jenis_kegiatan_id)
                ->where('status_absen', 'berhasil')
                ->whereYear('waktu_kegiatan', $tahunSekarang);
                
            if ($target->periode === 'bulanan') {
                $query->whereMonth('waktu_kegiatan', $bulanSekarang);
            }

            $capaian = $query->count();
            
            $progress[] = [
                'target' => $target,
                'capaian' => $capaian,
                'persentase' => min(100, round(($capaian / $target->jumlah_target) * 100))
            ];
        }

        // Riwayat absensi terbaru
        $riwayats = \App\Models\AbsensiKegiatan::with('jenisKegiatan')
            ->where('pengguna_id', $user->id)
            ->orderBy('waktu_kegiatan', 'desc')
            ->take(10)
            ->get();

        return view('kegiatan.absensi.index', compact('progress', 'riwayats'));
    }

    public function create()
    {
        $jenisKegiatans = \App\Models\JenisKegiatan::where('status', 'aktif')->orderBy('nama_kegiatan')->get();
        return view('kegiatan.absensi.create', compact('jenisKegiatans'));
    }

    public function store(Request $request, FaceRecognitionService $faceService)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'photo' => 'required|string', // base64 photo
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        // ---------------------------------------------------------
        // VERIFIKASI WAJAH via FaceRecognitionService
        // (Real AWS Rekognition jika key terkonfigurasi, atau MOCK jika tidak)
        // ---------------------------------------------------------
        $faceResult = $faceService->verifyFace($request->input('photo'));

        if (!$faceResult['success']) {
            // Catat absensi gagal
            \App\Models\AbsensiKegiatan::create([
                'pengguna_id'      => $user->id,
                'jenis_kegiatan_id'=> $request->jenis_kegiatan_id,
                'waktu_kegiatan'   => now(),
                'status_wajah'     => 'tidak_dikenali',
                'status_absen'     => 'gagal'
            ]);

            return back()->with('error', 'Wajah tidak dikenali! ' . $faceResult['message']);
        }

        // Pastikan wajah yang terdeteksi adalah milik user yang login
        if (isset($faceResult['user_id']) && $faceResult['user_id'] != $user->id) {
            return back()->with('error', 'Wajah yang terdeteksi tidak sesuai dengan akun Anda.');
        }

        // Catat absensi berhasil
        \App\Models\AbsensiKegiatan::create([
            'pengguna_id'       => $user->id,
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'waktu_kegiatan'    => now(),
            'status_wajah'      => 'dikenali',
            'status_absen'      => 'berhasil'
        ]);

        // Cek pencapaian target
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');
        
        $target = \App\Models\TargetKegiatan::where('jenis_kegiatan_id', $request->jenis_kegiatan_id)
            ->where('tahun', $tahunSekarang)
            ->where(function($q) use ($bulanSekarang) {
                $q->where('periode', 'tahunan')
                  ->orWhere(function($sq) use ($bulanSekarang) {
                      $sq->where('periode', 'bulanan')->where('bulan', $bulanSekarang);
                  });
            })
            ->orderBy('periode', 'asc')
            ->first();

        if ($target) {
            $query = \App\Models\AbsensiKegiatan::where('pengguna_id', $user->id)
                ->where('jenis_kegiatan_id', $request->jenis_kegiatan_id)
                ->where('status_absen', 'berhasil')
                ->whereYear('waktu_kegiatan', $tahunSekarang);
                
            if ($target->periode === 'bulanan') {
                $query->whereMonth('waktu_kegiatan', $bulanSekarang);
            }

            $capaian = $query->count();

            if ($capaian == $target->jumlah_target) {
                NotifikasiService::kirim(
                    $user->id,
                    'Target Tercapai! 🎉',
                    'Alhamdulillah, Anda telah mencapai target ' . $target->periode . ' untuk kegiatan ini.',
                    'success',
                    $target->id,
                    'target_kegiatan'
                );
            }
        }

        return redirect()->route('absensi-kegiatan.index')->with('success', 'Kegiatan berhasil dicatat! Barakallah fiikum.');
    }
}
