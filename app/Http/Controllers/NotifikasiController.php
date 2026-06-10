<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi milik user yang login.
     */
    public function index()
    {
        $notifikasis = Notifikasi::where('pengguna_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Tandai semua yang belum dibaca sebagai dibaca setelah membuka halaman
        Notifikasi::where('pengguna_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true, 'dibaca_pada' => now()]);

        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca (via AJAX atau direct link).
     */
    public function tandaiDibaca(int $id)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        $notif->update(['dibaca' => true, 'dibaca_pada' => now()]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Tandai SEMUA notifikasi user ini sebagai dibaca.
     */
    public function tandaiSemuaDibaca()
    {
        Notifikasi::where('pengguna_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true, 'dibaca_pada' => now()]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Hapus satu notifikasi.
     */
    public function hapus(int $id)
    {
        Notifikasi::where('id', $id)
            ->where('pengguna_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Return jumlah notifikasi belum dibaca (untuk API/AJAX header badge).
     */
    public function jumlahBelumDibaca()
    {
        $jumlah = Notifikasi::where('pengguna_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return response()->json(['jumlah' => $jumlah]);
    }
}

