<?php

namespace App\Services;

class GeoFenceService
{
    /**
     * Hitung jarak 2 titik (meter) - Haversine Formula
     */
    public function distanceInMeters(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) *
            pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    /**
     * Cek apakah posisi siswa valid
     */
    public function isInsideRadius(
        float $userLat,
        float $userLng,
        float $roomLat,
        float $roomLng,
        float $radius
    ): bool {
        $distance = $this->distanceInMeters(
            $userLat,
            $userLng,
            $roomLat,
            $roomLng
        );

        return $distance <= $radius;
    }
}
