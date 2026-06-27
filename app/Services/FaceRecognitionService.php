<?php

namespace App\Services;

use App\Models\PendaftaranWajah;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    /**
     * Daftarkan wajah pengguna baru ke database lokal (menyimpan face descriptors JSON)
     */
    public function enrollFace(User $user, $descriptorJson)
    {
        try {
            // Pastikan data adalah JSON array 128 float valid
            $descriptor = json_decode($descriptorJson, true);
            if (!is_array($descriptor) || count($descriptor) !== 128) {
                return [
                    'success' => false,
                    'message' => 'Format data wajah tidak valid. Pastikan wajah terdeteksi dengan jelas.',
                ];
            }

            // Simpan ke database
            $pendaftaran = PendaftaranWajah::updateOrCreate(
                ['pengguna_id' => $user->id],
                [
                    'aws_face_id' => $descriptorJson, // Kolom ini menyimpan JSON array koordinat wajah
                    'aws_collection_id' => 'local_face_api',
                    'status' => 'aktif',
                    'terdaftar_pada' => now(),
                ]
            );

            return [
                'success' => true,
                'message' => 'Wajah berhasil didaftarkan.',
                'face_id' => $descriptorJson
            ];

        } catch (\Exception $e) {
            Log::error('Local Face Enroll Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftarkan data wajah.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifikasi wajah (1:N search) dengan membandingkan Euclidean Distance deskriptor wajah
     */
    public function verifyFace($descriptorJson, $distanceThreshold = 0.6)
    {
        try {
            $inputDescriptor = json_decode($descriptorJson, true);
            if (!is_array($inputDescriptor) || count($inputDescriptor) !== 128) {
                return [
                    'success' => false,
                    'message' => 'Format data wajah tidak valid. Pastikan kamera mendeteksi wajah.'
                ];
            }

            $allPendaftaran = PendaftaranWajah::where('status', 'aktif')->get();

            $bestMatch = null;
            $minDistance = 999.0;

            foreach ($allPendaftaran as $pendaftaran) {
                $dbDescriptor = json_decode($pendaftaran->aws_face_id, true);
                if (!is_array($dbDescriptor) || count($dbDescriptor) !== 128) {
                    continue;
                }

                // Hitung Euclidean Distance antara input descriptor dengan database descriptor
                $distance = 0.0;
                for ($i = 0; $i < 128; $i++) {
                    $diff = $inputDescriptor[$i] - $dbDescriptor[$i];
                    $distance += $diff * $diff;
                }
                $distance = sqrt($distance);

                // Di face-api.js, batas jarak default kecocokan adalah < 0.6
                if ($distance < $distanceThreshold && $distance < $minDistance) {
                    $minDistance = $distance;
                    $bestMatch = $pendaftaran;
                }
            }

            if ($bestMatch) {
                // Konversi jarak ke persentase kemiripan (similarity) untuk tampilan
                $similarity = round((1.0 - ($minDistance / $distanceThreshold)) * 100, 2);
                return [
                    'success' => true,
                    'message' => 'Verifikasi wajah berhasil.',
                    'similarity' => $similarity,
                    'user_id' => $bestMatch->pengguna_id,
                    'face_id' => $bestMatch->aws_face_id
                ];
            }

            return [
                'success' => false,
                'message' => 'Wajah tidak dikenali atau tidak cocok.'
            ];

        } catch (\Exception $e) {
            Log::error('Local Face Verify Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem pengenal wajah lokal.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Dummy method to maintain compatibility
     */
    public function createCollectionIfNotExists()
    {
        return true;
    }
}
