<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp menggunakan API Fonnte.
     * Jika token Fonnte tidak diatur, pesan akan dicatat di log Laravel.
     */
    public static function send(string $to, string $message): bool
    {
        $token = env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::info("=== SIMULASI WHATSAPP ===");
            Log::info("Ke: " . $to);
            Log::info("Pesan: " . $message);
            Log::info("=========================");
            return true;
        }

        try {
            // Pastikan format nomor telepon sesuai (menggunakan kode negara, misal 62)
            $to = static::formatPhoneNumber($to);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp berhasil dikirim ke $to via Fonnte.");
                return true;
            }

            Log::error("Gagal mengirim WhatsApp ke $to. Respon Fonnte: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Error saat mengirim WhatsApp ke $to: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor telepon ke format internasional (misal: 0812 -> 62812)
     */
    private static function formatPhoneNumber(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan '0', ganti dengan '62'
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Jika sudah diawali dengan '62' atau lainnya, biarkan saja
        return $phone;
    }
}
