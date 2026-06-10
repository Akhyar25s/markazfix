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

        JadwalItikaf::create([
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
            'status' => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
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
            'status' => $request->status,
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
}
