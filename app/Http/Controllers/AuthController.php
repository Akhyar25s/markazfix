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
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'no_telepon'        => 'required|string|max:20',
            'password'          => 'required|string|min:8',
            'umur'              => 'required|integer|min:1|max:120',
            'wilayah_id'        => 'nullable',
            'asal_daerah'       => 'nullable|string|max:255',
            'mahallah_id'       => 'nullable|exists:mahallahs,id',
            'foto_wajah_depan'  => 'required|string',
            'foto_wajah_kiri'   => 'required|string',
            'foto_wajah_kanan'  => 'required|string',
        ]);

        // Tentukan wilayah_id: jika pilih "lainnya", cari wilayah Tamu
        $wilayahId = $request->wilayah_id;
        if ($wilayahId === 'lainnya' || empty($wilayahId)) {
            $wilayahTamu = \App\Models\Wilayah::where('nama_wilayah', 'Tamu')->first();
            $wilayahId = $wilayahTamu ? $wilayahTamu->id : null;
        }

        try {
            DB::beginTransaction();

            // 2. Buat User (Akun)
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'no_telepon'  => $request->no_telepon,
                'password'    => Hash::make($request->password),
                'umur'        => $request->umur,
                'asal_daerah' => $request->asal_daerah,
                'wilayah_id'  => $wilayahId,
                'mahallah_id' => $request->mahallah_id,
                'role'        => 'anggota',
                'status'      => 'aktif',
            ]);

            // 3. Simpan Face Descriptor (3 Sudut) dari face-api.js
            $angles = [
                'depan' => $request->foto_wajah_depan,
                'kiri'  => $request->foto_wajah_kiri,
                'kanan' => $request->foto_wajah_kanan
            ];

            foreach ($angles as $angleName => $descriptorJson) {
                // Pastikan data deskriptor valid JSON 128 float
                $descriptor = json_decode($descriptorJson, true);
                if (!is_array($descriptor) || count($descriptor) !== 128) {
                    throw new \Exception("Data wajah untuk sudut $angleName tidak valid. Pastikan wajah terdeteksi dengan jelas.");
                }

                // Catat ke tabel pendaftaran_wajahs
                PendaftaranWajah::create([
                    'pengguna_id'       => $user->id,
                    'aws_face_id'       => $descriptorJson, // Kolom ini menyimpan JSON array koordinat wajah
                    'aws_collection_id' => 'local_face_api',
                    'status'            => 'aktif'
                ]);
            }

            DB::commit();

            // 4. Login pengguna secara otomatis
            Auth::login($user);

            // 5. Redirect ke Dashboard
            return redirect('/dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Pendaftaran gagal: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Menangani proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Cek apakah input 'login' berupa email atau no telepon
        $loginValue = $request->input('login');
        $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_telepon';

        $credentials = [
            $loginField => $loginValue,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->status !== 'aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'login' => 'Akun Anda sedang tidak aktif. Silakan hubungi admin.',
                ])->onlyInput('login');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Email/Nomor HP atau kata sandi yang dimasukkan salah.',
        ])->onlyInput('login');
    }
}
