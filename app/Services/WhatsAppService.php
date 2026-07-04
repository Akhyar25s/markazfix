<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp menggunakan Baileys Gateway (self-hosted, gratis unlimited).
     * Fallback ke Fonnte jika Baileys tidak tersedia.
     * Jika keduanya tidak aktif, pesan dicatat di log Laravel.
     */
    public static function send(string $to, string $message): bool
    {
        // 1. Coba Baileys Gateway (gratis, self-hosted)
        $baileysUrl = env('WA_GATEWAY_URL', 'http://127.0.0.1:3002');

        try {
            $healthCheck = Http::timeout(5)->get($baileysUrl . '/status');

            if ($healthCheck->successful() && $healthCheck->json('connected') === true) {
                $response = Http::timeout(10)->post($baileysUrl . '/send', [
                    'target' => $to,
                    'message' => $message,
                ]);

                if ($response->successful() && $response->json('success') === true) {
                    Log::info("WhatsApp berhasil dikirim ke {$to} via Baileys.");
                    return true;
                }

                Log::error("Gagal kirim WhatsApp via Baileys ke {$to}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::warning("Baileys gateway tidak tersedia: " . $e->getMessage());
        }

        // 2. Fallback ke Fonnte jika ada token
        $fonnteToken = env('FONNTE_TOKEN');

        if (!empty($fonnteToken)) {
            try {
                $toFormatted = static::formatPhoneNumber($to);

                $response = Http::withHeaders([
                    'Authorization' => $fonnteToken,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $toFormatted,
                    'message' => $message,
                ]);

                if ($response->successful() && $response->json('status') === true) {
                    Log::info("WhatsApp berhasil dikirim ke {$toFormatted} via Fonnte (fallback).");
                    return true;
                }

                Log::error("Gagal kirim WhatsApp via Fonnte ke {$toFormatted}: " . $response->body());
            } catch (\Exception $e) {
                Log::error("Error Fonnte: " . $e->getMessage());
            }
        }

        // 3. Fallback terakhir: log simulasi
        Log::info("=== SIMULASI WHATSAPP ===");
        Log::info("Ke: " . $to);
        Log::info("Pesan: " . $message);
        Log::info("=========================");

        return false;
    }

    /**
     * Format nomor telepon ke format internasional (misal: 0812 -> 62812)
     */
    private static function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
