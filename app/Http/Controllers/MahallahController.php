<?php

namespace App\Http\Controllers;

use App\Models\Mahallah;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class MahallahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahallahs = Mahallah::with('wilayah')->paginate(10);
        return view('mahallah.index', compact('mahallahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wilayahs = Wilayah::where('status', 'aktif')->get();
        return view('mahallah.create', compact('wilayahs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mahallah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'wilayah_id' => 'required|exists:wilayahs,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Mahallah::create($validated);

        return redirect()->route('mahallah.index')->with('success', 'Data Mahallah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahallah $mahallah)
    {
        $mahallah->load(['wilayah', 'users' => function($query) {
            $query->where('role', 'anggota');
        }]);
        return view('mahallah.show', compact('mahallah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahallah $mahallah)
    {
        $wilayahs = Wilayah::where('status', 'aktif')->get();
        return view('mahallah.edit', compact('mahallah', 'wilayahs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahallah $mahallah)
    {
        $validated = $request->validate([
            'nama_mahallah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'wilayah_id' => 'required|exists:wilayahs,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $mahallah->update($validated);

        return redirect()->route('mahallah.index')->with('success', 'Data Mahallah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahallah $mahallah)
    {
        $mahallah->delete();
        return redirect()->route('mahallah.index')->with('success', 'Data Mahallah berhasil dihapus');
    }

    /**
     * Get data for geospatial map dashboard
     */
    public function getMapData()
    {
        $mahallahs = Mahallah::with('wilayah')->get();
        
        $data = $mahallahs->map(function ($mahallah) {
            return [
                'id' => $mahallah->id,
                'name' => $mahallah->nama_mahallah,
                'wilayah' => $mahallah->wilayah ? $mahallah->wilayah->nama_wilayah : 'Tidak Ada Wilayah',
                'lat' => $mahallah->latitude,
                'lng' => $mahallah->longitude,
                'status' => ucfirst($mahallah->status),
                'members' => $mahallah->users()->count(),
            ];
        })->filter(function ($item) {
            return !empty($item['lat']) && !empty($item['lng']);
        });

        return response()->json(array_values($data->toArray()));
    }
}
