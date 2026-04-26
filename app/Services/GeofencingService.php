<?php

namespace App\Services;

class GeofencingService
{
    /**
     * Menghitung jarak antara dua koordinat GPS menggunakan formula Haversine.
     * Mengembalikan jarak dalam satuan meter.
     *
     * @param float $lat1 Latitude titik 1
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusMeters = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusMeters * $c;
    }

    /**
     * Mengecek apakah suatu koordinat berada dalam radius yang ditentukan dari pusat.
     *
     * @param float $userLat Latitude user
     * @param float $userLon Longitude user
     * @param float $centerLat Latitude pusat (lokasi jadwal)
     * @param float $centerLon Longitude pusat (lokasi jadwal)
     * @param float $radiusMeters Radius yang diizinkan dalam meter
     * @return array ['is_within' => bool, 'distance' => float]
     */
    public function isWithinRadius(
        float $userLat,
        float $userLon,
        float $centerLat,
        float $centerLon,
        float $radiusMeters
    ): array {
        $distance = $this->calculateDistance($userLat, $userLon, $centerLat, $centerLon);

        return [
            'is_within'  => $distance <= $radiusMeters,
            'distance'   => round($distance, 2),
            'radius'     => $radiusMeters,
        ];
    }
}
