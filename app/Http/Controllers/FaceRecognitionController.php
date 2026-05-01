<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceRecognitionService;
use App\Services\GeofencingService;
use App\Models\JadwalItikaf;
use App\Models\PesertaItikaf;
use App\Models\PendaftaranWajah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    protected $faceService;
    protected $geofencingService;

    public function __construct(FaceRecognitionService $faceService, GeofencingService $geofencingService)
    {
        $this->faceService       = $faceService;
        $this->geofencingService = $geofencingService;
    }

    /**
     * Show the face enrollment view
     */
    public function showEnrollmentForm()
    {
        $user = Auth::user();

        $isRegistered = PendaftaranWajah::where('pengguna_id', $user->id)
                            ->where('status', 'aktif')
                            ->exists();

        return view('face.enroll', compact('user', 'isRegistered'));
    }

    /**
     * Handle face enrollment submission
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $user        = Auth::user();
        $imageBase64 = $request->input('image');

        $result = $this->faceService->enrollFace($user, $imageBase64);

        if ($result['success']) {
            return response()->json(['success' => true, 'message' => $result['message']]);
        }

        return response()->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * Show the face verification (absensi) view for a specific jadwal
     */
    public function showVerificationForm(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');

        // Cari jadwal yang sedang berlangsung atau dijadwalkan
        $jadwal = null;
        if ($jadwalId) {
            $jadwal = JadwalItikaf::find($jadwalId);
        } else {
            // Jika tidak ada jadwal_id, coba cari jadwal yang sedang berlangsung
            $jadwal = JadwalItikaf::where('status', 'berlangsung')->latest()->first();
        }

        if (!$jadwal) {
            return redirect('/dashboard')
                ->with('error', 'Tidak ada jadwal I\'tikaf yang aktif saat ini.');
        }

        return view('face.verify', compact('jadwal'));
    }

    /**
     * Handle face verification with geofencing check
     */
    public function verify(Request $request)
    {
        $request->validate([
            'image'     => 'required|string',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'jadwal_id' => 'required|integer|exists:jadwal_itikafs,id',
        ]);

        $jadwal = JadwalItikaf::find($request->jadwal_id);

        // ============================================================
        // STEP 1: VALIDASI GEOFENCING (Server-Side)
        // ============================================================
        if (is_null($jadwal->latitude) || is_null($jadwal->longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat lokasi jadwal belum dikonfigurasi oleh admin.',
            ], 422);
        }

        $geoCheck = $this->geofencingService->isWithinRadius(
            userLat:   $request->latitude,
            userLon:   $request->longitude,
            centerLat: $jadwal->latitude,
            centerLon: $jadwal->longitude,
            radiusMeters: $jadwal->radius_meter,
        );

        if (!$geoCheck['is_within']) {
            return response()->json([
                'success'  => false,
                'type'     => 'geofence_error',
                'message'  => 'Anda berada di luar zona yang diizinkan. Jarak Anda: ' .
                              round($geoCheck['distance']) . ' m (Batas: ' . $geoCheck['radius'] . ' m)',
                'distance' => $geoCheck['distance'],
            ], 403);
        }

        // ============================================================
        // STEP 2: VALIDASI WAJAH (AWS Rekognition 1:N)
        // ============================================================
        $imageBase64 = $request->input('image');
        $faceResult  = $this->faceService->verifyFace($imageBase64);

        if (!$faceResult['success']) {
            return response()->json([
                'success' => false,
                'type'    => 'face_error',
                'message' => $faceResult['message'],
            ], 400);
        }

        // ============================================================
        // STEP 3: CARI USER BERDASARKAN FACE ID YANG DIKENALI
        // ============================================================
        $awsFaceId = $faceResult['face_id'] ?? null;
        $pendaftaranWajah = null;

        if ($awsFaceId) {
            $pendaftaranWajah = PendaftaranWajah::where('aws_face_id', $awsFaceId)
                                    ->where('status', 'aktif')
                                    ->with('pengguna')
                                    ->first();
        }

        if (!$pendaftaranWajah) {
            return response()->json([
                'success' => false,
                'type'    => 'face_error',
                'message' => 'Wajah dikenali, namun tidak ditemukan di database peserta terdaftar.',
            ], 404);
        }

        $peserta = $pendaftaranWajah->pengguna;

        // ============================================================
        // STEP 4: CEK APAKAH PESERTA TERDAFTAR UNTUK JADWAL INI
        // ============================================================
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

        // ============================================================
        // STEP 5: CATAT ABSENSI (cegah duplikasi dalam satu hari)
        // ============================================================
        $today = Carbon::today();

        $sudahAbsen = DB::table('absensi_itikafs')
                        ->where('jadwal_itikaf_id', $jadwal->id)
                        ->where('pengguna_id', $peserta->id)
                        ->where('status_absen', 'berhasil')
                        ->whereDate('waktu_absen', $today)
                        ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => true,
                'type'    => 'already_checked_in',
                'message' => 'Selamat datang, ' . $peserta->name . '! Anda sudah melakukan absensi hari ini.',
                'nama'    => $peserta->name,
            ]);
        }

        DB::table('absensi_itikafs')->insert([
            'jadwal_itikaf_id' => $jadwal->id,
            'pengguna_id'      => $peserta->id,
            'waktu_absen'      => Carbon::now(),
            'latitude_aktual'  => $request->latitude,
            'longitude_aktual' => $request->longitude,
            'jarak_meter'      => (int) round($geoCheck['distance']),
            'status_gps'       => 'valid',
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
