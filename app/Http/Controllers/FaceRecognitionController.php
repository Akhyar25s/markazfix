<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalItikaf;
use App\Models\PesertaItikaf;
use App\Models\PendaftaranWajah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    /**
     * Show the face enrollment view
     */
    public function showEnrollmentForm()
    {
        $user = Auth::user();
        $isRegistered = PendaftaranWajah::where('pengguna_id', $user->id)
                            ->where('status', 'aktif')
                            ->whereNotNull('face_descriptor')
                            ->exists();
        return view('face.enroll', compact('user', 'isRegistered'));
    }

    /**
     * Save face descriptor from face-api.js (client-side)
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'face_descriptor' => 'required|string',
        ]);

        $user = Auth::user();

        // Simpan descriptor ke database
        PendaftaranWajah::updateOrCreate(
            ['pengguna_id' => $user->id],
            [
                'face_descriptor'   => $request->face_descriptor,
                'aws_face_id'       => null,
                'aws_collection_id' => null,
                'status'            => 'aktif',
                'terdaftar_pada'    => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Wajah berhasil didaftarkan! Kamu sekarang bisa melakukan presensi dengan wajah.',
        ]);
    }

    /**
     * API: Return all face descriptors for client-side matching
     */
    public function getFaceDescriptors()
    {
        $descriptors = PendaftaranWajah::where('status', 'aktif')
            ->whereNotNull('face_descriptor')
            ->with('pengguna:id,name')
            ->get()
            ->map(fn($d) => [
                'pengguna_id'     => $d->pengguna_id,
                'nama'            => $d->pengguna->name ?? 'Unknown',
                'face_descriptor' => json_decode($d->face_descriptor),
            ]);

        return response()->json($descriptors);
    }

    /**
     * Show the face verification (absensi) view
     */
    public function showVerificationForm(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $jadwal = null;

        if ($jadwalId) {
            $jadwal = JadwalItikaf::find($jadwalId);
        } else {
            $jadwal = JadwalItikaf::where('status', 'berlangsung')->latest()->first();
        }

        if (!$jadwal) {
            return redirect('/dashboard')
                ->with('error', 'Tidak ada jadwal I\'tikaf yang aktif saat ini.');
        }

        return view('face.verify', compact('jadwal'));
    }

    /**
     * Record attendance after client-side face match
     */
    public function verify(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|integer|exists:users,id',
            'jadwal_id'   => 'required|integer|exists:jadwal_itikafs,id',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $jadwal  = JadwalItikaf::find($request->jadwal_id);
        $peserta = \App\Models\User::find($request->pengguna_id);

        // Cek apakah peserta terdaftar di jadwal ini
        $isPeserta = PesertaItikaf::where('jadwal_itikaf_id', $jadwal->id)
                        ->where('pengguna_id', $peserta->id)
                        ->exists();

        if (!$isPeserta) {
            return response()->json([
                'success' => false,
                'type'    => 'not_registered',
                'message' => $peserta->name . ' tidak terdaftar sebagai peserta jadwal I\'tikaf ini.',
            ], 403);
        }

        // Cegah duplikasi absensi hari ini
        $sudahAbsen = DB::table('absensi_itikafs')
                        ->where('jadwal_itikaf_id', $jadwal->id)
                        ->where('pengguna_id', $peserta->id)
                        ->where('status_absen', 'berhasil')
                        ->whereDate('waktu_absen', Carbon::today())
                        ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => true,
                'type'    => 'already_checked_in',
                'message' => 'Selamat datang, ' . $peserta->name . '! Anda sudah melakukan absensi hari ini.',
                'nama'    => $peserta->name,
            ]);
        }

        // Catat absensi
        DB::table('absensi_itikafs')->insert([
            'jadwal_itikaf_id' => $jadwal->id,
            'pengguna_id'      => $peserta->id,
            'waktu_absen'      => Carbon::now(),
            'latitude_aktual'  => $request->latitude,
            'longitude_aktual' => $request->longitude,
            'status_wajah'     => 'dikenali',
            'status_absen'     => 'berhasil',
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'type'    => 'checked_in',
            'message' => 'Absensi berhasil dicatat! Selamat datang, ' . $peserta->name . '.',
            'nama'    => $peserta->name,
        ]);
    }
}
