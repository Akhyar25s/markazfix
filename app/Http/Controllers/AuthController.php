<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PendaftaranWajah;
use App\Services\AwsRekognitionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Menampilkan form registrasi
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses pendaftaran dan Face Enrollment
     */
    public function register(Request $request, AwsRekognitionService $awsRekognition)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telepon' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'wilayah_id' => 'nullable|exists:wilayahs,id',
            'mahallah_id' => 'nullable|exists:mahallahs,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'foto_wajah_depan' => 'required|string',
            'foto_wajah_kiri' => 'required|string',
            'foto_wajah_kanan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Buat User (Akun)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password),
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'wilayah_id' => $request->wilayah_id,
                'mahallah_id' => $request->mahallah_id,
                'role' => 'anggota',
                'status' => 'aktif',
            ]);

            // 3. Proses Foto Wajah (3 Sudut)
            $angles = [
                'depan' => $request->foto_wajah_depan,
                'kiri' => $request->foto_wajah_kiri,
                'kanan' => $request->foto_wajah_kanan
            ];

            foreach ($angles as $angleName => $base64String) {
                $imageParts = explode(";base64,", $base64String);
                
                if (count($imageParts) != 2) {
                    throw new \Exception("Format foto $angleName tidak valid.");
                }
                
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $imageBase64 = base64_decode($imageParts[1]);

                $fileName = 'temp_face_' . $angleName . '_' . uniqid() . '.' . $imageType;
                $tempPath = 'temp_faces/' . $fileName;
                
                Storage::disk('local')->put($tempPath, $imageBase64);
                $fullPath = storage_path('app/private/' . $tempPath);
                
                if (!file_exists($fullPath)) {
                    $fullPath = storage_path('app/' . $tempPath);
                }

                // Panggil Service AWS Rekognition
                $faceData = $awsRekognition->indexFace($fullPath, $user->id);

                if (!$faceData) {
                    DB::rollBack();
                    Storage::disk('local')->delete($tempPath);
                    return back()->withErrors(['foto_wajah_depan' => "Wajah tidak terdeteksi pada foto $angleName. Pastikan pencahayaan cukup dan wajah terlihat jelas."])->withInput();
                }

                // 4. Catat ke tabel pendaftaran_wajahs
                PendaftaranWajah::create([
                    'pengguna_id' => $user->id,
                    'aws_face_id' => $faceData['FaceId'],
                    'aws_collection_id' => env('AWS_REKOGNITION_COLLECTION_ID', 'markaz_faces'),
                    'status' => 'aktif'
                ]);

                // Hapus file sementara setelah selesai diproses
                Storage::disk('local')->delete($tempPath);
            }

            DB::commit();

            // 5. Login pengguna secara otomatis
            Auth::login($user);

            // 6. Redirect ke Dashboard
            return redirect('/dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Menangani proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->status !== 'aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akun Anda sedang tidak aktif. Silakan hubungi admin.',
                ])->onlyInput('email');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang dimasukkan salah.',
        ])->onlyInput('email');
    }
}
