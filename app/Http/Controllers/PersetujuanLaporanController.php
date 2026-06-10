<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanItikaf;
use App\Models\JadwalItikaf;
use App\Services\NotifikasiService;

class PersetujuanLaporanController extends Controller
{
    /**
     * Daftar laporan yang perlu di-review, disesuaikan dengan role user
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pengurus_wilayah') {
            // Tampilkan laporan dari jadwal i'tikaf yang ada peserta dari wilayahnya
            // dan status = menunggu_wilayah
            $laporan = LaporanItikaf::with(['jadwal', 'amir'])
                ->where('status', 'menunggu_wilayah')
                ->whereHas('jadwal.pesertas', function($q) use ($user) {
                    $q->where('wilayah_id', $user->wilayah_id);
                })
                ->orderBy('dikirim_pada', 'asc')
                ->get();

            $title = 'Laporan Masuk dari Amir';

        } elseif ($user->role === 'pengurus_inti') {
            // Tampilkan laporan dengan status menunggu_inti (sudah di-approve Wilayah)
            $laporan = LaporanItikaf::with(['jadwal', 'amir'])
                ->where('status', 'menunggu_inti')
                ->orderBy('dikirim_pada', 'asc')
                ->get();

            $title = 'Laporan Masuk dari Pengurus Wilayah';

        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('persetujuan.index', compact('laporan', 'title'));
    }

    /**
     * Detail laporan untuk di-review
     */
    public function show($id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::with(['jadwal', 'amir'])->findOrFail($id);

        // Validasi akses
        if ($user->role === 'pengurus_wilayah') {
            if ($laporan->status !== 'menunggu_wilayah') {
                abort(404);
            }
        } elseif ($user->role === 'pengurus_inti') {
            if ($laporan->status !== 'menunggu_inti') {
                abort(404);
            }
        } else {
            abort(403);
        }

        // Ambil data peserta yang hadir dari ID list di laporan
        $pesertaHadirIds = $laporan->peserta_hadir ?? [];
        $pesertaHadir = DB::table('users')
            ->whereIn('id', $pesertaHadirIds)
            ->select('id', 'name', 'email')
            ->get();

        return view('persetujuan.show', compact('laporan', 'pesertaHadir'));
    }

    /**
     * Setujui laporan dan teruskan ke level berikutnya
     */
    public function approve($id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::findOrFail($id);

        if ($user->role === 'pengurus_wilayah') {
            if ($laporan->status !== 'menunggu_wilayah') {
                return back()->with('error', 'Status laporan tidak valid untuk disetujui.');
            }
            $laporan->update(['status' => 'menunggu_inti']);
            $msg = 'Laporan disetujui dan diteruskan ke Pengurus Inti.';

            // Notifikasi ke Pengurus Inti
            NotifikasiService::notifyPengurusInti(
                'Persetujuan Laporan Sesi',
                'Pengurus Wilayah telah meneruskan laporan sesi "' . $laporan->nama_sesi . '" untuk persetujuan final Anda.',
                'info',
                $laporan->id
            );

        } elseif ($user->role === 'pengurus_inti') {
            if ($laporan->status !== 'menunggu_inti') {
                return back()->with('error', 'Status laporan tidak valid untuk disetujui.');
            }
            $laporan->update([
                'status'         => 'disetujui',
                'disetujui_pada' => now(),
            ]);
            $msg = 'Laporan disetujui secara final.';

            // Notifikasi ke Amir
            NotifikasiService::kirim(
                $laporan->amir_id,
                'Laporan Disetujui',
                'Laporan sesi "' . $laporan->nama_sesi . '" telah disetujui secara final oleh Pengurus Inti.',
                'success',
                $laporan->id,
                'laporan_itikaf'
            );

        } else {
            abort(403);
        }

        return redirect()->route('persetujuan.index')->with('success', $msg);
    }

    /**
     * Kembalikan laporan dengan catatan revisi
     */
    public function reject(Request $request, $id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::findOrFail($id);

        $request->validate([
            'catatan' => 'required|string|min:10',
        ]);

        if ($user->role === 'pengurus_wilayah') {
            if ($laporan->status !== 'menunggu_wilayah') {
                return back()->with('error', 'Status laporan tidak valid.');
            }
            $laporan->update([
                'status'           => 'dikembalikan_wilayah',
                'catatan_wilayah'  => $request->catatan,
            ]);
            $msg = 'Laporan dikembalikan ke Amir untuk direvisi.';

            // Notifikasi ke Amir
            NotifikasiService::kirim(
                $laporan->amir_id,
                'Revisi Laporan Sesi',
                'Laporan sesi "' . $laporan->nama_sesi . '" dikembalikan oleh Pengurus Wilayah. Silakan periksa catatan revisi.',
                'warning',
                $laporan->id,
                'laporan_itikaf'
            );

        } elseif ($user->role === 'pengurus_inti') {
            if ($laporan->status !== 'menunggu_inti') {
                return back()->with('error', 'Status laporan tidak valid.');
            }
            $laporan->update([
                'status'       => 'dikembalikan_inti',
                'catatan_inti' => $request->catatan,
            ]);
            $msg = 'Laporan dikembalikan ke Pengurus Wilayah untuk ditinjau ulang.';

            // Notifikasi ke Amir (dan secara teknis bisa juga ke Wilayah)
            NotifikasiService::kirim(
                $laporan->amir_id,
                'Revisi Laporan Sesi (Dari Inti)',
                'Laporan sesi "' . $laporan->nama_sesi . '" dikembalikan oleh Pengurus Inti. Silakan perbaiki laporan Anda.',
                'warning',
                $laporan->id,
                'laporan_itikaf'
            );

        } else {
            abort(403);
        }

        return redirect()->route('persetujuan.index')->with('success', $msg);
    }
}
