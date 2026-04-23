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
     * Verifikasi wajah saat absensi
     */
    public function verifyFace(User $user, $imageBase64, $similarityThreshold = 95.0)
    {
        try {
            $pendaftaran = PendaftaranWajah::where('pengguna_id', $user->id)->where('status', 'aktif')->first();
            
            if (!$pendaftaran) {
                return [
                    'success' => false,
                    'message' => 'Wajah Anda belum terdaftar di sistem. Silakan hubungi admin.'
                ];
            }

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
                    'message' => 'Wajah tidak dikenali atau tidak cocok. Silakan coba lagi.'
                ];
            }

            $matchedFaceId = $result['FaceMatches'][0]['Face']['FaceId'];
            $similarity = $result['FaceMatches'][0]['Similarity'];

            if ($matchedFaceId === $pendaftaran->aws_face_id) {
                return [
                    'success' => true,
                    'message' => 'Verifikasi wajah berhasil.',
                    'similarity' => $similarity
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Wajah yang dipindai tidak cocok dengan data pendaftaran Anda.'
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
