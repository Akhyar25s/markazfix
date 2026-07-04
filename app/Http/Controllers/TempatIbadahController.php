<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatIbadah;
use App\Models\Mahallah;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TempatIbadahController extends Controller
{
    /**
     * Tampilkan daftar tempat ibadah
     */
    public function index()
    {
        $tempatIbadahs = TempatIbadah::with('mahallah')->orderBy('nama')->get();
        return view('tempat_ibadah.index', compact('tempatIbadahs'));
    }

    /**
     * Tampilkan form tambah tempat ibadah baru
     */
    public function create(Request $request)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $mahallahs = Mahallah::all();
        $selectedMahallahId = $request->query('mahallah_id');

        return view('tempat_ibadah.create', compact('mahallahs', 'selectedMahallahId'));
    }

    /**
     * Simpan tempat ibadah baru
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'nama'         => 'required|string|max:150',
            'jenis'        => 'required|in:masjid,langgar,mushola,lainnya',
            'mahallah_id'  => 'required|exists:mahallahs,id',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('tempat-ibadah-foto', 'public');
        }

        TempatIbadah::create([
            'nama'         => $request->nama,
            'jenis'        => $request->jenis,
            'mahallah_id'  => $request->mahallah_id,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'foto'         => $fotoPath,
        ]);

        return redirect()->route('mahallah.show', $request->mahallah_id)
            ->with('success', 'Tempat ibadah "' . $request->nama . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail tempat ibadah
     */
    public function show($id)
    {
        $tempatIbadah = TempatIbadah::with('mahallah')->findOrFail($id);
        return view('tempat_ibadah.show', compact('tempatIbadah'));
    }

    /**
     * Tampilkan form edit tempat ibadah
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $tempatIbadah = TempatIbadah::findOrFail($id);
        $mahallahs    = Mahallah::all();

        return view('tempat_ibadah.edit', compact('tempatIbadah', 'mahallahs'));
    }

    /**
     * Update tempat ibadah
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $tempatIbadah = TempatIbadah::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:150',
            'jenis'        => 'required|in:masjid,langgar,mushola,lainnya',
            'mahallah_id'  => 'required|exists:mahallahs,id',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $fotoPath = $tempatIbadah->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($tempatIbadah->foto) {
                Storage::disk('public')->delete($tempatIbadah->foto);
            }
            $fotoPath = $request->file('foto')->store('tempat-ibadah-foto', 'public');
        }

        $tempatIbadah->update([
            'nama'         => $request->nama,
            'jenis'        => $request->jenis,
            'mahallah_id'  => $request->mahallah_id,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'foto'         => $fotoPath,
        ]);

        return redirect()->route('mahallah.show', $request->mahallah_id)
            ->with('success', 'Tempat ibadah "' . $request->nama . '" berhasil diperbarui.');
    }

    /**
     * Hapus tempat ibadah
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'pengurus_inti') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $tempatIbadah = TempatIbadah::findOrFail($id);
        $mahallahId   = $tempatIbadah->mahallah_id;
        $nama         = $tempatIbadah->nama;

        if ($tempatIbadah->foto) {
            Storage::disk('public')->delete($tempatIbadah->foto);
        }

        $tempatIbadah->delete();

        return redirect()->route('mahallah.show', $mahallahId)
            ->with('success', 'Tempat ibadah "' . $nama . '" berhasil dihapus.');
    }
}
