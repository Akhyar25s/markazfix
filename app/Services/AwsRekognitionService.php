<?php

namespace App\Services;

use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class AwsRekognitionService
{
    protected $client;
    protected $collectionId;
    protected $isMock = false;

    public function __construct()
    {
        $this->collectionId = env('AWS_REKOGNITION_COLLECTION_ID', 'markaz_faces');

        // Cek apakah AWS Key tersedia
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');

        if (empty($key) || empty($secret)) {
            $this->isMock = true;
            Log::info("AWS_ACCESS_KEY_ID tidak diset. AwsRekognitionService berjalan dalam mode MOCK.");
        } else {
            $this->client = new RekognitionClient([
                'region'  => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
                'version' => 'latest',
                'credentials' => [
                    'key'    => $key,
                    'secret' => $secret,
                ]
            ]);
        }
    }

    /**
     * Mendaftarkan wajah pengguna ke AWS Rekognition Collection
     *
     * @param string $imagePath Path relatif atau absolut ke file gambar
     * @param string $externalImageId ID eksternal (misal: user_id)
     * @return array|null Mengembalikan data wajah atau null jika gagal
     */
    public function indexFace($imagePath, $externalImageId)
    {
        if ($this->isMock) {
            // Mode Simulasi (Mock)
            Log::info("MOCK AWS Rekognition: Indexing face untuk user {$externalImageId}");
            return [
                'FaceId' => 'mock-face-id-' . uniqid(),
                'ImageId' => 'mock-image-id-' . uniqid(),
                'Confidence' => 99.99
            ];
        }

        try {
            $imageBytes = file_get_contents($imagePath);

            $result = $this->client->indexFaces([
                'CollectionId' => $this->collectionId,
                'ExternalImageId' => (string) $externalImageId,
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
                'DetectionAttributes' => ['DEFAULT'],
                'MaxFaces' => 1,
                'QualityFilter' => 'AUTO',
            ]);

            $faceRecords = $result->get('FaceRecords');
            
            if (!empty($faceRecords)) {
                $face = $faceRecords[0]['Face'];
                return [
                    'FaceId' => $face['FaceId'],
                    'ImageId' => $face['ImageId'],
                    'Confidence' => $face['Confidence']
                ];
            }

            Log::warning("AWS Rekognition: Wajah tidak ditemukan dalam gambar.");
            return null;

        } catch (AwsException $e) {
            Log::error("AWS Rekognition Error: " . $e->getMessage());
            return null;
        }
    }
}
