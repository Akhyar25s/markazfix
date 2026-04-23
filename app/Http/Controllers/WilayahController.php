<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wilayahs = Wilayah::with('pengurus')->paginate(10);
        return view('wilayah.index', compact('wilayahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wilayah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wilayah' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pengurus_id' => 'nullable|exists:users,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Wilayah::create($validated);

        return redirect()->route('wilayah.index')->with('success', 'Data Wilayah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wilayah $wilayah)
    {
        $wilayah->load(['pengurus', 'mahallahs']);
        return view('wilayah.show', compact('wilayah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wilayah $wilayah)
    {
        return view('wilayah.edit', compact('wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $request->validate([
            'nama_wilayah' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pengurus_id' => 'nullable|exists:users,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $wilayah->update($validated);

        return redirect()->route('wilayah.index')->with('success', 'Data Wilayah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();
        return redirect()->route('wilayah.index')->with('success', 'Data Wilayah berhasil dihapus');
    }
}
