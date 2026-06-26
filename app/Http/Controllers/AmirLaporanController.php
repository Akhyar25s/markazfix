<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\LaporanItikaf;
use App\Models\JadwalItikaf;
use App\Models\PesertaItikaf;
use Carbon\Carbon;
use App\Services\NotifikasiService;

class AmirLaporanController extends Controller
{
    /**
     * Daftar jadwal i'tikaf di mana user ini adalah Amir
     */
    public function index()
    {
        $user = Auth::user();

        // Cari jadwal i'tikaf di mana user ini adalah Amir (adalah_amir = true)
        $jadwals = DB::table('peserta_itikafs as p')
            ->join('jadwal_itikafs as j', 'j.id', '=', 'p.jadwal_itikaf_id')
            ->where('p.pengguna_id', $user->id)
            ->where('p.adalah_amir', true)
            ->select('j.*', DB::raw('(SELECT COUNT(*) FROM laporan_itikafs WHERE jadwal_itikaf_id = j.id AND amir_id = '.$user->id.') as jumlah_laporan'))
            ->orderBy('j.tanggal_mulai', 'desc')
            ->get();

        return view('amir.laporan.index', compact('jadwals'));
    }

    /**
     * Daftar laporan sesi untuk jadwal tertentu
     */
    public function show($jadwal_id)
    {
        $user = Auth::user();
        $jadwal = JadwalItikaf::findOrFail($jadwal_id);

        // Pastikan user ini memang Amir dari jadwal tsb
        $isAmir = DB::table('peserta_itikafs')
            ->where('jadwal_itikaf_id', $jadwal_id)
            ->where('pengguna_id', $user->id)
            ->where('adalah_amir', true)
            ->exists();

        if (!$isAmir) {
            abort(403, 'Anda bukan Amir pada jadwal i\'tikaf ini.');
        }

        $laporan = LaporanItikaf::where('jadwal_itikaf_id', $jadwal_id)
            ->where('amir_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('amir.laporan.show', compact('jadwal', 'laporan'));
    }

    /**
     * Form buat laporan sesi baru
     */
    public function create($jadwal_id)
    {
        $user = Auth::user();
        $jadwal = JadwalItikaf::findOrFail($jadwal_id);

        // Pastikan user ini memang Amir dari jadwal tsb
        $isAmir = DB::table('peserta_itikafs')
            ->where('jadwal_itikaf_id', $jadwal_id)
            ->where('pengguna_id', $user->id)
            ->where('adalah_amir', true)
            ->exists();

        if (!$isAmir) {
            abort(403, 'Anda bukan Amir pada jadwal i\'tikaf ini.');
        }

        // Ambil daftar semua peserta pada jadwal ini (untuk checklist kehadiran)
        $peserta = DB::table('peserta_itikafs as p')
            ->join('users as u', 'u.id', '=', 'p.pengguna_id')
            ->where('p.jadwal_itikaf_id', $jadwal_id)
            ->select('u.id', 'u.name')
            ->orderBy('u.name')
            ->get();

        return view('amir.laporan.create', compact('jadwal', 'peserta'));
    }

    /**
     * Simpan laporan sesi baru (status: draft)
     */
    public function store(Request $request, $jadwal_id)
    {
        $user = Auth::user();
        $jadwal = JadwalItikaf::findOrFail($jadwal_id);

        $isAmir = DB::table('peserta_itikafs')
            ->where('jadwal_itikaf_id', $jadwal_id)
            ->where('pengguna_id', $user->id)
            ->where('adalah_amir', true)
            ->exists();

        if (!$isAmir) abort(403);

        $SESI_TETAP = ['Bayan Subuh', 'Talim Pagi', 'Talim Zhuhur', 'Talim Ashar', 'Bayan Maghrib', 'Talim Akhir'];

        $request->validate([
            'nama_sesi'          => 'required|string|in:' . implode(',', $SESI_TETAP),
            'tanggal_kegiatan'   => 'required|date|after_or_equal:' . $jadwal->tanggal_mulai . '|before_or_equal:' . $jadwal->tanggal_selesai,
            'waktu_mulai'        => 'required|date_format:H:i',
            'waktu_selesai'      => 'required|date_format:H:i',
            'uraian_kegiatan'    => 'required|string',
            'peserta_hadir'      => 'nullable|array',
            'dokumen'            => 'nullable|array|max:5',
            'dokumen.*'          => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Gabungkan tanggal dan waktu
        $waktuMulai   = $request->tanggal_kegiatan . ' ' . $request->waktu_mulai . ':00';
        $waktuSelesai = $request->tanggal_kegiatan . ' ' . $request->waktu_selesai . ':00';

        DB::beginTransaction();
        try {
            // Upload dokumen pendukung jika ada
            $dokumenPaths = [];
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    $path = $file->store('laporan-dokumen/' . $jadwal_id, 'public');
                    $dokumenPaths[] = [
                        'nama'   => $file->getClientOriginalName(),
                        'path'   => $path,
                        'tipe'   => $file->getClientMimeType(),
                        'ukuran' => $file->getSize(),
                    ];
                }
            }

            LaporanItikaf::create([
                'jadwal_itikaf_id'  => $jadwal_id,
                'amir_id'           => $user->id,
                'nama_sesi'         => $request->nama_sesi,
                'waktu_mulai'       => $waktuMulai,
                'waktu_selesai'     => $waktuSelesai,
                'uraian_kegiatan'   => $request->uraian_kegiatan,
                'peserta_hadir'     => $request->peserta_hadir ?? [],
                'dokumen_pendukung' => $dokumenPaths,
                'status'            => 'draft',
            ]);

            DB::commit();
            return redirect()->route('amir.laporan.show', $jadwal_id)
                ->with('success', 'Laporan sesi berhasil disimpan sebagai draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit laporan (hanya bisa jika status draft atau dikembalikan)
     */
    public function edit($id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::with('jadwal')->findOrFail($id);

        // Pastikan laporan milik Amir ini dan statusnya masih bisa diedit
        if ($laporan->amir_id !== $user->id) abort(403);
        if (!in_array($laporan->status, ['draft', 'dikembalikan_wilayah', 'dikembalikan_inti'])) {
            return back()->with('error', 'Laporan yang sudah dikirim tidak bisa diedit.');
        }

        $peserta = DB::table('peserta_itikafs as p')
            ->join('users as u', 'u.id', '=', 'p.pengguna_id')
            ->where('p.jadwal_itikaf_id', $laporan->jadwal_itikaf_id)
            ->select('u.id', 'u.name')
            ->orderBy('u.name')
            ->get();

        return view('amir.laporan.edit', compact('laporan', 'peserta'));
    }

    /**
     * Update laporan sesi
     */
    public function update(Request $request, $id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::findOrFail($id);

        if ($laporan->amir_id !== $user->id) abort(403);
        if (!in_array($laporan->status, ['draft', 'dikembalikan_wilayah', 'dikembalikan_inti'])) {
            return back()->with('error', 'Laporan yang sudah dikirim tidak bisa diedit.');
        }

        $SESI_TETAP = ['Bayan Subuh', 'Talim Pagi', 'Talim Zhuhur', 'Talim Ashar', 'Bayan Maghrib', 'Talim Akhir'];

        $request->validate([
            'nama_sesi'       => 'required|string|in:' . implode(',', $SESI_TETAP),
            'waktu_mulai'     => 'required|date',
            'waktu_selesai'   => 'required|date|after:waktu_mulai',
            'uraian_kegiatan' => 'required|string',
            'peserta_hadir'   => 'nullable|array',
            'dokumen'         => 'nullable|array|max:5',
            'dokumen.*'       => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $dokumenPaths = $laporan->dokumen_pendukung ?? [];
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    $path = $file->store('laporan-dokumen/' . $laporan->jadwal_itikaf_id, 'public');
                    $dokumenPaths[] = [
                        'nama'  => $file->getClientOriginalName(),
                        'path'  => $path,
                        'tipe'  => $file->getClientMimeType(),
                        'ukuran' => $file->getSize(),
                    ];
                }
            }

            $laporan->update([
                'nama_sesi'        => $request->nama_sesi,
                'waktu_mulai'      => $request->waktu_mulai,
                'waktu_selesai'    => $request->waktu_selesai,
                'uraian_kegiatan'  => $request->uraian_kegiatan,
                'peserta_hadir'    => $request->peserta_hadir ?? [],
                'dokumen_pendukung' => $dokumenPaths,
                // Reset status catatan jika sedang direvisi
                'catatan_wilayah'  => null,
                'catatan_inti'     => null,
                'status'           => 'draft',
            ]);

            DB::commit();
            return redirect()->route('amir.laporan.show', $laporan->jadwal_itikaf_id)
                ->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Kirim laporan ke Pengurus Wilayah
     */
    public function kirim($id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::findOrFail($id);

        if ($laporan->amir_id !== $user->id) abort(403);
        if (!in_array($laporan->status, ['draft', 'dikembalikan_wilayah', 'dikembalikan_inti'])) {
            return back()->with('error', 'Laporan ini tidak bisa dikirim ulang.');
        }

        $laporan->update([
            'status'       => 'menunggu_wilayah',
            'dikirim_pada' => now(),
        ]);

        // Kirim Notifikasi ke Pengurus Wilayah
        NotifikasiService::notifyPengurusWilayah(
            'Laporan Sesi Baru',
            'Amir I\'tikaf telah mengirimkan laporan sesi "' . $laporan->nama_sesi . '" untuk ditinjau.',
            'info',
            $laporan->id
        );

        return redirect()->route('amir.laporan.show', $laporan->jadwal_itikaf_id)
            ->with('success', 'Laporan berhasil dikirim ke Pengurus Wilayah.');
    }

    /**
     * Hapus dokumen pendukung tertentu dari laporan
     */
    public function hapusDokumen(Request $request, $id)
    {
        $user   = Auth::user();
        $laporan = LaporanItikaf::findOrFail($id);

        if ($laporan->amir_id !== $user->id) abort(403);

        $index = $request->input('index');
        $dokumen = $laporan->dokumen_pendukung ?? [];

        if (isset($dokumen[$index])) {
            Storage::disk('public')->delete($dokumen[$index]['path']);
            array_splice($dokumen, $index, 1);
            $laporan->update(['dokumen_pendukung' => array_values($dokumen)]);
        }

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
