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
        if (Auth::user()->role !== 'pengurus_wilayah') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        // Tampilkan semua jadwal yang statusnya 'dijadwalkan' atau 'berlangsung'
        $jadwals = JadwalItikaf::with('mahallah')
                    ->whereIn('status', ['dijadwalkan', 'berlangsung'])
                    ->orderBy('tanggal_mulai', 'asc')
                    ->get();
                    
        return view('peserta.index', compact('jadwals'));
    }

    /**
     * Menampilkan form pendaftaran peserta untuk jadwal tertentu
     */
    public function create($jadwal_id)
    {
        if (Auth::user()->role !== 'pengurus_wilayah') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $jadwal = JadwalItikaf::findOrFail($jadwal_id);
        $user = Auth::user();

        // Ambil semua anggota yang berada di wilayah pengurus ini
        $anggotas = User::where('role', 'anggota')
                        ->where('wilayah_id', $user->wilayah_id)
                        ->where('status', 'aktif')
                        ->get();

        // Ambil ID anggota yang sudah terdaftar di jadwal ini
        $pesertaTerdaftar = PesertaItikaf::where('jadwal_id', $jadwal_id)->pluck('pengguna_id')->toArray();

        return view('peserta.create', compact('jadwal', 'anggotas', 'pesertaTerdaftar'));
    }

    /**
     * Menyimpan data peserta ke jadwal
     */
    public function store(Request $request, $jadwal_id)
    {
        if (Auth::user()->role !== 'pengurus_wilayah') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'pengguna_ids' => 'required|array',
            'pengguna_ids.*' => 'exists:users,id'
        ]);

        $jadwal = JadwalItikaf::findOrFail($jadwal_id);
        
        try {
            DB::beginTransaction();

            // Pada sistem nyata, mungkin perlu mengecek kapasitas maksimal jadwal

            foreach ($request->pengguna_ids as $pengguna_id) {
                // Cek apakah sudah terdaftar
                $exists = PesertaItikaf::where('jadwal_id', $jadwal_id)
                                       ->where('pengguna_id', $pengguna_id)
                                       ->exists();
                
                if (!$exists) {
                    PesertaItikaf::create([
                        'jadwal_id' => $jadwal_id,
                        'pengguna_id' => $pengguna_id,
                        'status_pendaftaran' => 'disetujui'
                    ]);
                }
            }

            DB::commit();
            return redirect('/peserta')->with('success', count($request->pengguna_ids) . ' peserta berhasil didaftarkan ke jadwal.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mendaftarkan peserta: ' . $e->getMessage());
        }
    }
}
