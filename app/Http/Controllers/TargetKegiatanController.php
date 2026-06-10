<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetKegiatan;

class TargetKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $targetKegiatans = TargetKegiatan::with(['jenisKegiatan', 'penetap'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
            
        return view('kegiatan.target.index', compact('targetKegiatans'));
    }

    public function create()
    {
        $jenisKegiatans = \App\Models\JenisKegiatan::where('status', 'aktif')->orderBy('nama_kegiatan')->get();
        return view('kegiatan.target.form', compact('jenisKegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'jumlah_target' => 'required|integer|min:1',
            'periode' => 'required|in:bulanan,tahunan',
            'tahun' => 'required|integer|min:2024',
            'bulan' => 'nullable|integer|min:1|max:12',
        ]);

        $data = $request->all();
        $data['ditetapkan_oleh'] = \Illuminate\Support\Facades\Auth::id();

        if ($data['periode'] === 'tahunan') {
            $data['bulan'] = null; // Tahun tidak pakai bulan
        }

        TargetKegiatan::create($data);
        return redirect()->route('target-kegiatan.index')->with('success', 'Target kegiatan berhasil ditetapkan.');
    }

    public function edit(TargetKegiatan $targetKegiatan)
    {
        $jenisKegiatans = \App\Models\JenisKegiatan::where('status', 'aktif')->orderBy('nama_kegiatan')->get();
        return view('kegiatan.target.form', compact('targetKegiatan', 'jenisKegiatans'));
    }

    public function update(Request $request, TargetKegiatan $targetKegiatan)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'jumlah_target' => 'required|integer|min:1',
            'periode' => 'required|in:bulanan,tahunan',
            'tahun' => 'required|integer|min:2024',
            'bulan' => 'nullable|integer|min:1|max:12',
        ]);

        $data = $request->all();
        if ($data['periode'] === 'tahunan') {
            $data['bulan'] = null;
        }

        $targetKegiatan->update($data);
        return redirect()->route('target-kegiatan.index')->with('success', 'Target kegiatan berhasil diperbarui.');
    }

    public function destroy(TargetKegiatan $targetKegiatan)
    {
        $targetKegiatan->delete();
        return redirect()->route('target-kegiatan.index')->with('success', 'Target kegiatan berhasil dihapus.');
    }
}
