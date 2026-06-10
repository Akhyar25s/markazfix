<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalItikaf;
use App\Models\Mahallah;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class JadwalItikafController extends Controller
{
    /**
     * Menampilkan daftar jadwal i'tikaf
     */
    public function index()
    {
        // Jika pengurus inti, tampilkan semua jadwal
        if (Auth::user()->role === 'pengurus_inti') {
            $jadwals = JadwalItikaf::with(['pembuat'])->orderBy('tanggal_mulai', 'desc')->get();
            return view('jadwal.index', compact('jadwals'));
        }
        
        // Selain itu, tolak akses (sementara kembali ke dashboard)
        return redirect('/dashboard')->with('error', 'Akses ditolak.');
    }

    /**
     * Menampilkan form pembuatan jadwal baru
     */
    public function create()
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $mahallahs = Mahallah::all();
        return view('jadwal.create', compact('mahallahs'));
    }

    /**
     * Menyimpan jadwal i'tikaf baru ke database
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama_itikaf' => 'required|string|max:150',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_lokasi' => 'required|string|max:150',
            'radius_meter' => 'required|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $jadwal = JadwalItikaf::create([
            'nama_itikaf' => $request->nama_itikaf,
            'keterangan' => $request->keterangan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nama_lokasi' => $request->nama_lokasi,
            'radius_meter' => $request->radius_meter,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'dibuat_oleh' => Auth::id(),
            'status' => 'dijadwalkan',
        ]);

        // Notifikasi ke Pengurus Wilayah
        NotifikasiService::notifyPengurusWilayah(
            'Jadwal I\'tikaf Baru',
            'Jadwal i\'tikaf baru "' . $jadwal->nama_itikaf . '" telah dibuat. Silakan daftarkan peserta.',
            'info',
            $jadwal->id
        );

        return redirect('/jadwal')->with('success', 'Jadwal I\'tikaf berhasil dibuat!');
    }

    /**
     * Menampilkan form edit jadwal
     */
    public function edit(JadwalItikaf $jadwal)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $mahallahs = Mahallah::all();
        return view('jadwal.edit', compact('jadwal', 'mahallahs'));
    }

    /**
     * Memperbarui jadwal i'tikaf di database
     */
    public function update(Request $request, JadwalItikaf $jadwal)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama_itikaf' => 'required|string|max:150',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_lokasi' => 'required|string|max:150',
            'radius_meter' => 'required|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $jadwal->update([
            'nama_itikaf' => $request->nama_itikaf,
            'keterangan' => $request->keterangan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nama_lokasi' => $request->nama_lokasi,
            'radius_meter' => $request->radius_meter,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect('/jadwal')->with('success', 'Jadwal I\'tikaf berhasil diperbarui!');
    }

    /**
     * Menghapus jadwal i'tikaf
     */
    public function destroy(JadwalItikaf $jadwal)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $jadwal->delete();
        return redirect('/jadwal')->with('success', 'Jadwal I\'tikaf berhasil dihapus!');
    }

    /**
     * Menampilkan daftar peserta pada jadwal tertentu (Untuk Pengurus Inti menunjuk Amir)
     */
    public function peserta($id)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $jadwal = JadwalItikaf::with(['pesertas.pengguna.wilayah', 'pesertas.pemilih'])->findOrFail($id);
        return view('jadwal.peserta', compact('jadwal'));
    }

    /**
     * Menunjuk peserta menjadi Amir I'tikaf
     */
    public function jadikanAmir(Request $request, $jadwal_id, $peserta_id)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $jadwal = JadwalItikaf::findOrFail($jadwal_id);
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Reset semua peserta di jadwal ini menjadi bukan amir
            \App\Models\PesertaItikaf::where('jadwal_itikaf_id', $jadwal_id)
                ->update(['adalah_amir' => false]);

            // 2. Set peserta terpilih sebagai amir
            $pesertaTerpilih = \App\Models\PesertaItikaf::where('jadwal_itikaf_id', $jadwal_id)
                ->where('id', $peserta_id)
                ->firstOrFail();
            
            $pesertaTerpilih->update([
                'adalah_amir' => true,
                'dipilih_oleh' => Auth::id()
            ]);

            // Notifikasi ke Amir terpilih
            NotifikasiService::kirim(
                $pesertaTerpilih->pengguna_id,
                'Penugasan Amir I\'tikaf',
                'Anda telah ditunjuk sebagai Amir I\'tikaf untuk jadwal "' . $jadwal->nama_itikaf . '".',
                'info',
                $jadwal->id,
                'jadwal_itikaf'
            );

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Peserta ' . $pesertaTerpilih->pengguna->name . ' berhasil ditunjuk sebagai Amir I\'tikaf.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menunjuk amir: ' . $e->getMessage());
        }
    }
}
