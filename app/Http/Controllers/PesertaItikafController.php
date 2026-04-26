<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalItikaf;
use App\Models\PesertaItikaf;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesertaItikafController extends Controller
{
    /**
     * Menampilkan daftar jadwal yang bisa didaftarkan peserta
     */
    public function index()
    {
        // Tampilkan semua jadwal yang statusnya 'dijadwalkan' atau 'berlangsung'
        $jadwals = JadwalItikaf::whereIn('status', ['dijadwalkan', 'berlangsung'])
                    ->orderBy('tanggal_mulai', 'asc')
                    ->get();
                    
        return view('peserta.index', compact('jadwals'));
    }

    /**
     * Menampilkan form pendaftaran peserta untuk jadwal tertentu
     */
    public function create($jadwal_id)
    {
        $jadwal = JadwalItikaf::findOrFail($jadwal_id);
        $user = Auth::user();

        // SCOPE: Ambil semua anggota yang berada di wilayah pengurus ini
        $anggotas = User::where('role', 'anggota')
                        ->where('wilayah_id', $user->wilayah_id)
                        ->where('status', 'aktif')
                        ->get();

        // Ambil ID anggota yang sudah terdaftar di jadwal ini (untuk semua wilayah, agar tidak double register)
        $pesertaTerdaftar = PesertaItikaf::where('jadwal_itikaf_id', $jadwal_id)->pluck('pengguna_id')->toArray();

        return view('peserta.create', compact('jadwal', 'anggotas', 'pesertaTerdaftar'));
    }

    /**
     * Menyimpan data peserta ke jadwal
     */
    public function store(Request $request, $jadwal_id)
    {
        $request->validate([
            'pengguna_ids' => 'required|array',
            'pengguna_ids.*' => 'exists:users,id'
        ]);

        $jadwal = JadwalItikaf::findOrFail($jadwal_id);
        $user = Auth::user();
        
        try {
            DB::beginTransaction();

            foreach ($request->pengguna_ids as $pengguna_id) {
                // SECURITY CHECK: Pastikan user yang didaftarkan memang milik wilayah pengurus ini
                $anggota = User::where('id', $pengguna_id)
                               ->where('wilayah_id', $user->wilayah_id)
                               ->first();

                if (!$anggota) continue; // Skip jika mencoba mendaftarkan user wilayah lain

                // Cek apakah sudah terdaftar
                $exists = PesertaItikaf::where('jadwal_itikaf_id', $jadwal_id)
                                       ->where('pengguna_id', $pengguna_id)
                                       ->exists();
                
                if (!$exists) {
                    PesertaItikaf::create([
                        'jadwal_itikaf_id' => $jadwal_id,
                        'pengguna_id' => $pengguna_id,
                        'status_pendaftaran' => 'disetujui'
                    ]);
                }
            }

            DB::commit();
            return redirect('/peserta')->with('success', 'Peserta berhasil didaftarkan ke jadwal.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mendaftarkan peserta: ' . $e->getMessage());
        }
    }
}
