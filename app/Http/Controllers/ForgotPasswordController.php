<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Services\WhatsAppService;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan form input email/whatsapp
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Membuat OTP & Token lalu mengirimkannya ke WA atau Email
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ], [
            'login.required' => 'Email atau Nomor WhatsApp wajib diisi.',
        ]);

        $loginValue = $request->input('login');
        
        // Cek apakah input berupa email atau no telepon
        $isEmail = filter_var($loginValue, FILTER_VALIDATE_EMAIL);
        
        if ($isEmail) {
            $user = User::where('email', $loginValue)->first();
        } else {
            // Bersihkan nomor telepon untuk pencarian di database
            // Misal di DB disimpannya 0812..., 62812..., atau +62812...
            // Kita coba cari yang mirip
            $cleanPhone = preg_replace('/[^0-9]/', '', $loginValue);
            
            // Validasi: nomor telepon harus minimal 6 digit setelah sanitasi
            if (strlen($cleanPhone) < 6) {
                return back()->withErrors(['login' => 'Nomor telepon tidak valid.'])->withInput();
            }
            
            // Cari nomor telepon yang mirip di database
            $user = User::where(function($q) use ($cleanPhone) {
                $q->where('no_telepon', 'like', '%' . $cleanPhone)
                  ->orWhere('no_telepon', 'like', '%' . ltrim($cleanPhone, '0'))
                  ->orWhere('no_telepon', 'like', '%' . ltrim($cleanPhone, '62'));
            })->first();
        }

        if (!$user) {
            return back()->withErrors(['login' => 'Akun dengan email atau nomor WhatsApp tersebut tidak ditemukan.'])->withInput();
        }

        // Hapus OTP lama untuk identifier ini jika ada
        PasswordResetOtp::where('identifier', $isEmail ? $user->email : $user->no_telepon)->delete();

        // Generate OTP 6 Digit
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate Token unik untuk Link Reset
        $token = Str::random(60);

        // Simpan ke database
        PasswordResetOtp::create([
            'identifier' => $isEmail ? $user->email : $user->no_telepon,
            'otp'        => $otp,
            'token'      => $token,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        $resetUrl = route('password.reset', ['token' => $token]);

        if ($isEmail) {
            // Kirim via Email
            try {
                Mail::to($user->email)->send(new ResetPasswordMail($user->name, $otp, $resetUrl));
            } catch (\Exception $e) {
                return back()->withErrors(['login' => 'Gagal mengirim email: ' . $e->getMessage()]);
            }
        } else {
            // Kirim via WhatsApp
            $message = "*[MARKAZ - Asisten I'tikaf]*\n\n"
                     . "Assalamu'alaikum Wr. Wb. Bpk/Ibu *" . $user->name . "*,\n\n"
                     . "Kami menerima permintaan untuk mengatur ulang kata sandi akun *MARKAZ (Aplikasi Informasi Masjid & I'tikaf)* Anda.\n\n"
                     . "🔑 *Kode Verifikasi OTP Anda:*\n"
                     . "*[ " . $otp . " ]*\n\n"
                     . "Masukkan kode di atas pada halaman verifikasi di aplikasi.\n\n"
                     . "Atau, Anda juga bisa langsung mengeklik tautan di bawah ini untuk mengatur ulang kata sandi Anda:\n"
                     . $resetUrl . "\n\n"
                     . "_Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan pesan ini demi keamanan akun Anda._\n"
                     . "_Jangan bagikan kode verifikasi ini kepada siapa pun._";

            $sent = WhatsAppService::send($user->no_telepon, $message);
            if (!$sent) {
                return back()->withErrors(['login' => 'Gagal mengirim pesan WhatsApp. Silakan coba lagi.']);
            }
        }

        // Simpan identifier di session untuk proses verifikasi OTP berikutnya
        session(['reset_identifier' => $isEmail ? $user->email : $user->no_telepon]);

        return redirect()->route('password.otp.verify')->with('success', 'Kode verifikasi telah dikirim ke ' . ($isEmail ? 'email' : 'nomor WhatsApp') . ' Anda.');
    }

    /**
     * Menampilkan form verifikasi OTP
     */
    public function showOtpVerifyForm()
    {
        if (!session('reset_identifier')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    /**
     * Memverifikasi kode OTP yang dimasukkan pengguna
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi lengkap.',
        ]);

        $identifier = session('reset_identifier');
        if (!$identifier) {
            return redirect()->route('password.request');
        }

        // Gabungkan array OTP menjadi string 6 digit
        $otpCode = implode('', $request->otp);

        $resetOtp = PasswordResetOtp::where('identifier', $identifier)
            ->where('otp', $otpCode)
            ->first();

        if (!$resetOtp) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        if ($resetOtp->isExpired()) {
            $resetOtp->delete();
            return redirect()->route('password.request')->withErrors(['login' => 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        // OTP valid, lanjut ke halaman ganti password menggunakan tokennya
        return redirect()->route('password.reset', ['token' => $resetOtp->token]);
    }

    /**
     * Menampilkan form setel ulang password baru
     */
    public function showResetForm($token)
    {
        $resetOtp = PasswordResetOtp::where('token', $token)->first();

        if (!$resetOtp) {
            return redirect()->route('password.request')->withErrors(['login' => 'Tautan pemulihan tidak valid atau sudah digunakan.']);
        }

        if ($resetOtp->isExpired()) {
            $resetOtp->delete();
            return redirect()->route('password.request')->withErrors(['login' => 'Tautan pemulihan telah kedaluwarsa. Silakan minta tautan baru.']);
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Menyimpan password baru
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.required'     => 'Kata sandi baru wajib diisi.',
            'password.min'          => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $resetOtp = PasswordResetOtp::where('token', $request->token)->first();

        if (!$resetOtp) {
            return redirect()->route('password.request')->withErrors(['login' => 'Permintaan tidak valid. Silakan coba lagi.']);
        }

        if ($resetOtp->isExpired()) {
            $resetOtp->delete();
            return redirect()->route('password.request')->withErrors(['login' => 'Sesi Anda telah berakhir. Silakan minta kode baru.']);
        }

        // Cari user berdasarkan email atau nomor telepon
        $user = User::where('email', $resetOtp->identifier)
            ->orWhere('no_telepon', $resetOtp->identifier)
            ->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['login' => 'Pengguna tidak ditemukan.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus data OTP dari database agar tidak bisa dipakai lagi
        $resetOtp->delete();

        // Bersihkan session
        session()->forget('reset_identifier');

        // Otomatis login pengguna
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Kata sandi Anda berhasil diperbarui dan Anda telah masuk ke sistem.');
    }
}
