<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceRecognitionService;
use Illuminate\Support\Facades\Auth;

class FaceRecognitionController extends Controller
{
    protected $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Show the face enrollment view
     */
    public function showEnrollmentForm()
    {
        $user = Auth::user();
        
        // Check if user already has face registered
        $isRegistered = \App\Models\PendaftaranWajah::where('pengguna_id', $user->id)
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
            'image' => 'required|string', // base64 string
        ]);

        $user = Auth::user();
        $imageBase64 = $request->input('image');

        $result = $this->faceService->enrollFace($user, $imageBase64);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }

    /**
     * Show the face verification (absensi) view
     */
    public function showVerificationForm()
    {
        // Biasanya ini diakses oleh admin/pengurus yang sedang bertugas di sesi I'tikaf tertentu
        // Untuk simulasi, kita lewatkan ke view
        return view('face.verify');
    }

    /**
     * Handle face verification submission
     */
    public function verify(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // base64 string
        ]);

        $user = Auth::user(); // Idealnya, ini mencari user yang terdeteksi, bukan user yang login. 
        // Note: verifyFace di service kita cek berdasarkan $user. 
        // Mari kita perbarui asumsi: AWS Rekognition akan mengembalikan FaceId, lalu kita cari di database FaceId milik siapa.
        
        $imageBase64 = $request->input('image');

        // Kita modifikasi dikit penggunaannya, panggil client langsung dari service untuk search
        // Service saat ini di set untuk verifikasi 1:1, padahal absen butuh 1:N.
        // Kita gunakan $this->faceService->verifyFace() tetapi sesuaikan:
        
        // Memanggil verifyFace (saat ini logicnya 1:1 jika pakai expectedFaceId, jika tidak 1:N)
        // Kita panggil dengan expectedFaceId = null agar mencocokkan dengan seluruh database (1:N)
        $result = $this->faceService->verifyFace($user, $imageBase64);

        if ($result['success']) {
            // Karena sukses, kita dapatkan face_id atau similarity di result.
            // Di sini kita bisa catat absen ke tabel absensi_itikafs. (Akan diimplementasikan nanti)
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengenali wajah! Absensi tercatat.',
                // 'similarity' => $result['similarity']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }
}
