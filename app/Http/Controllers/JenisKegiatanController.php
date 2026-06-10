<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKegiatan;

class JenisKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKegiatans = JenisKegiatan::orderBy('nama_kegiatan')->get();
        return view('kegiatan.jenis.index', compact('jenisKegiatans'));
    }

    public function create()
    {
        return view('kegiatan.jenis.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        JenisKegiatan::create($request->all());
        return redirect()->route('jenis-kegiatan.index')->with('success', 'Jenis kegiatan berhasil ditambahkan.');
    }

    public function edit(JenisKegiatan $jenisKegiatan)
    {
        return view('kegiatan.jenis.form', compact('jenisKegiatan'));
    }

    public function update(Request $request, JenisKegiatan $jenisKegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jenisKegiatan->update($request->all());
        return redirect()->route('jenis-kegiatan.index')->with('success', 'Jenis kegiatan berhasil diperbarui.');
    }

    public function destroy(JenisKegiatan $jenisKegiatan)
    {
        // Cek apakah sudah digunakan di target atau absensi
        if ($jenisKegiatan->status == 'aktif') {
            $jenisKegiatan->update(['status' => 'nonaktif']);
            return redirect()->route('jenis-kegiatan.index')->with('success', 'Jenis kegiatan dinonaktifkan (tidak dihapus secara permanen).');
        }

        $jenisKegiatan->delete();
        return redirect()->route('jenis-kegiatan.index')->with('success', 'Jenis kegiatan berhasil dihapus.');
    }
}
