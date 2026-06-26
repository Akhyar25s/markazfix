<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PendaftaranWajah;
use App\Models\Wilayah;
use App\Services\AwsRekognitionService;

class TamuController extends Controller
{
    /**
     * Tampilkan form pendaftaran tamu (hanya untuk Amir & Pengurus)
     */
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['pengurus_inti', 'pengurus_wilayah', 'anggota'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        // Cek apakah user adalah Amir pada jadwal aktif
        $isAmir = DB::table('peserta_itikafs')
            ->where('pengguna_id', $user->id)
            ->where('adalah_amir', true)
            ->exists();

        if (!$isAmir && !in_array($user->role, ['pengurus_inti', 'pengurus_wilayah'])) {
            return redirect('/dashboard')->with('error', 'Hanya Amir I\'tikaf atau Pengurus yang dapat mendaftarkan tamu.');
        }

        return view('tamu.create');
    }

    /**
     * Simpan pendaftaran tamu baru
     */
    public function store(Request $request, AwsRekognitionService $awsRekognition)
    {
        $user = Auth::user();
        $isAmir = DB::table('peserta_itikafs')
            ->where('pengguna_id', $user->id)
            ->where('adalah_amir', true)
            ->exists();

        if (!$isAmir && !in_array($user->role, ['pengurus_inti', 'pengurus_wilayah'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name'             => 'required|string|max:255',
            'asal_daerah'      => 'required|string|max:255',
            'foto_wajah_depan' => 'required|string',
        ]);

        // Ambil atau buat wilayah "Tamu"
        $wilayahTamu = Wilayah::firstOrCreate(
            ['nama_wilayah' => 'Tamu'],
            ['status' => 'aktif', 'dibuat_oleh' => 1]
        );

        // Generate email unik untuk tamu
        $emailTamu = 'tamu_' . time() . '_' . rand(1000, 9999) . '@markaz.tamu';

        DB::beginTransaction();
        try {
            // Buat akun tamu
            $tamu = User::create([
                'name'        => $request->name,
                'email'       => $emailTamu,
                'password'    => Hash::make(\Illuminate\Support\Str::random(16)),
                'asal_daerah' => $request->asal_daerah,
                'wilayah_id'  => $wilayahTamu->id,
                'role'        => 'anggota',
                'status'      => 'tamu',
            ]);

            // Proses foto wajah
            $base64String = $request->foto_wajah_depan;
            $imageParts = explode(";base64,", $base64String);

            if (count($imageParts) != 2) {
                throw new \Exception("Format foto tidak valid.");
            }

            $imageTypeAux = explode("image/", $imageParts[0]);
            $imageType    = $imageTypeAux[1] ?? 'jpg';
            $imageBase64  = base64_decode($imageParts[1]);

            $fileName = 'temp_tamu_' . $tamu->id . '_' . uniqid() . '.' . $imageType;
            $tempPath = 'temp_faces/' . $fileName;
            Storage::disk('local')->put($tempPath, $imageBase64);
            $fullPath = storage_path('app/private/' . $tempPath);
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/' . $tempPath);
            }

            $faceData = $awsRekognition->indexFace($fullPath, $tamu->id);
            Storage::disk('local')->delete($tempPath);

            if (!$faceData) {
                DB::rollBack();
                return back()->withErrors(['foto_wajah_depan' => 'Wajah tidak terdeteksi. Pastikan pencahayaan cukup.'])->withInput();
            }

            PendaftaranWajah::create([
                'pengguna_id'       => $tamu->id,
                'aws_face_id'       => $faceData['FaceId'],
                'aws_collection_id' => env('AWS_REKOGNITION_COLLECTION_ID', 'markaz_faces'),
                'status'            => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('tamu.create')->with('success', 'Tamu "' . $tamu->name . '" berhasil didaftarkan dan siap melakukan absensi!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mendaftarkan tamu: ' . $e->getMessage()])->withInput();
        }
    }
}
