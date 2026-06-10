<?php

namespace App\Services;

use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;
use App\Models\PendaftaranWajah;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    protected $client;
    protected $collectionId;

    public function __construct()
    {
        $this->client = new RekognitionClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        
        $this->collectionId = env('AWS_REKOGNITION_COLLECTION', 'markaz_faces');
    }

    /**
     * Daftarkan wajah pengguna baru ke AWS Rekognition Collection
     */
    public function enrollFace(User $user, $imageBase64)
    {
        // ==========================================
        // MOCK MODE: Bypass AWS Rekognition
        // ==========================================
        if (env('USE_MOCK_FACE', false)) {
            $faceId = 'mock_face_' . uniqid();
            PendaftaranWajah::updateOrCreate(
                ['pengguna_id' => $user->id],
                [
                    'aws_face_id' => $faceId,
                    'aws_collection_id' => 'mock_collection',
                    'status' => 'aktif',
                    'terdaftar_pada' => now(),
                ]
            );
            return [
                'success' => true,
                'message' => 'Wajah berhasil didaftarkan (Mode Simulasi tanpa AWS).',
                'face_id' => $faceId
            ];
        }

        try {
            // Remove data:image/jpeg;base64, part if exists
            if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64)) {
                $data = substr($imageBase64, strpos($imageBase64, ',') + 1);
            } else {
                $data = $imageBase64;
            }
            
            $imageBytes = base64_decode($data);

            $result = $this->client->indexFaces([
                'CollectionId' => $this->collectionId,
                'DetectionAttributes' => ['DEFAULT'],
                'ExternalImageId' => 'user_' . $user->id,
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
                'MaxFaces' => 1,
                'QualityFilter' => 'AUTO',
            ]);

            if (empty($result['FaceRecords'])) {
                return [
                    'success' => false,
                    'message' => 'Wajah tidak terdeteksi dalam foto. Silakan coba lagi dengan pencahayaan yang lebih baik.',
                ];
            }

            $faceId = $result['FaceRecords'][0]['Face']['FaceId'];

            // Simpan ke database
            PendaftaranWajah::updateOrCreate(
                ['pengguna_id' => $user->id],
                [
                    'aws_face_id' => $faceId,
                    'aws_collection_id' => $this->collectionId,
                    'status' => 'aktif',
                    'terdaftar_pada' => now(),
                ]
            );

            return [
                'success' => true,
                'message' => 'Wajah berhasil didaftarkan.',
                'face_id' => $faceId
            ];

        } catch (AwsException $e) {
            Log::error('AWS Rekognition Enroll Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi server pengenal wajah.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifikasi wajah saat absensi (1:N search)
     */
    public function verifyFace($imageBase64, $similarityThreshold = 90.0)
    {
        // ==========================================
        // MOCK MODE: Bypass AWS Rekognition
        // ==========================================
        if (env('USE_MOCK_FACE', false)) {
            $pendaftaran = PendaftaranWajah::where('status', 'aktif')->latest()->first();
            if ($pendaftaran) {
                return [
                    'success' => true,
                    'message' => 'Verifikasi wajah berhasil (Mode Simulasi).',
                    'similarity' => 99.9,
                    'face_id' => $pendaftaran->aws_face_id,
                    'user_id' => $pendaftaran->pengguna_id
                ];
            }
            return [
                'success' => false,
                'message' => 'Belum ada data wajah terdaftar di sistem simulasi.'
            ];
        }

        try {
            // Remove data:image/jpeg;base64, part if exists
            if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64)) {
                $data = substr($imageBase64, strpos($imageBase64, ',') + 1);
            } else {
                $data = $imageBase64;
            }
            
            $imageBytes = base64_decode($data);

            $result = $this->client->searchFacesByImage([
                'CollectionId' => $this->collectionId,
                'FaceMatchThreshold' => $similarityThreshold,
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
                'MaxFaces' => 1,
            ]);

            if (empty($result['FaceMatches'])) {
                return [
                    'success' => false,
                    'message' => 'Wajah tidak dikenali atau tidak cocok.'
                ];
            }

            $matchedFaceId = $result['FaceMatches'][0]['Face']['FaceId'];
            $similarity = $result['FaceMatches'][0]['Similarity'];

            $pendaftaran = PendaftaranWajah::where('aws_face_id', $matchedFaceId)->where('status', 'aktif')->first();

            if ($pendaftaran) {
                return [
                    'success' => true,
                    'message' => 'Verifikasi wajah berhasil.',
                    'similarity' => $similarity,
                    'user_id' => $pendaftaran->pengguna_id,
                    'face_id' => $matchedFaceId // Menambahkan face_id untuk mencegah null di Controller
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Wajah cocok namun tidak ada di data pendaftaran sistem.'
                ];
            }

        } catch (AwsException $e) {
            Log::error('AWS Rekognition Verify Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem pengenal wajah.',
                'error' => $e->getMessage()
            ];
        }
    }

    
    /**
     * Utility method untuk memastikan collection ada
     */
    public function createCollectionIfNotExists()
    {
        try {
            $collections = $this->client->listCollections()->get('CollectionIds');
            if (!in_array($this->collectionId, $collections)) {
                $this->client->createCollection([
                    'CollectionId' => $this->collectionId
                ]);
                return true;
            }
            return false;
        } catch (AwsException $e) {
            Log::error('AWS Collection Create Error: ' . $e->getMessage());
            return false;
        }
    }
}
