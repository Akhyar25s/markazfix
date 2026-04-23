<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalItikaf;
use App\Models\Mahallah;
use Illuminate\Support\Facades\Auth;

class JadwalItikafController extends Controller
{
    /**
     * Menampilkan daftar jadwal i'tikaf
     */
    public function index()
    {
        // Jika pengurus inti, tampilkan semua jadwal
        if (Auth::user()->role === 'pengurus_inti') {
            $jadwals = JadwalItikaf::with(['mahallah', 'pembuat'])->orderBy('tanggal_mulai', 'desc')->get();
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
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_mahallah_id' => 'required|exists:mahallahs,id',
            'kapasitas_maksimal' => 'required|integer|min:1',
        ]);

        JadwalItikaf::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_mahallah_id' => $request->target_mahallah_id,
            'dibuat_oleh' => Auth::id(),
            'kapasitas_maksimal' => $request->kapasitas_maksimal,
            'status' => 'akan_datang',
        ]);

        return redirect('/jadwal')->with('success', 'Jadwal I\'tikaf berhasil dibuat!');
    }
}
