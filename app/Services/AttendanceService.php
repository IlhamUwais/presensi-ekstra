<?php

namespace App\Services;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    protected GeoFenceService $geoFenceService;

    public function __construct(GeoFenceService $geoFenceService)
    {
        $this->geoFenceService = $geoFenceService;
    }

       private function validateLocation(Schedule $schedule, $lat, $lng)
    {
        if (empty($lat) || empty($lng)) {
            throw ValidationException::withMessages(['gps' => 'GPS belum aktif atau lokasi tidak terdeteksi.']);
        }

        $room = $schedule->roomEkstra;
        // Jika tidak ada setting ruangan, anggap valid (atau sesuaikan kebutuhan)
        if (!$room || !$room->latitude || !$room->longitude) return;

        $isInside = $this->geoFenceService->isInsideRadius(
            $lat,
            $lng,
            $room->latitude,
            $room->longitude,
            $room->radius ?? 50
        );

        if (!$isInside) {
            throw ValidationException::withMessages(['location' => 'Anda berada di luar radius lokasi absen.']);
        }
    }

    public function clockIn(User $user, Schedule $schedule, $lat, $lng, $photoBase64)
    {
        // 1. Validasi Lokasi
        $this->validateLocation($schedule, $lat, $lng);

        // 2. Validasi Foto
        if (!$photoBase64) {
            throw ValidationException::withMessages(['photo' => 'Foto wajib diambil untuk absen masuk.']);
        }

        // 3. Simpan Data
        $attendance = Attendance::firstOrCreate([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'date' => today(),
        ]);

        $attendance->update([
            'clock_in' => now(),
            'photo_in' => $this->storePhoto($photoBase64),
            'lat_in' => $lat,
            'long_in' => $lng,
            'status' => 'hadir'
        ]);
    }

    public function clockOut(User $user, Schedule $schedule, $lat, $lng, $photoBase64)
    {
        $this->validateLocation($schedule, $lat, $lng);

        if (!$photoBase64) {
            throw ValidationException::withMessages(['photo' => 'Foto wajib diambil untuk absen pulang.']);
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            throw ValidationException::withMessages(['attendance' => 'Data absen masuk tidak ditemukan.']);
        }

        $attendance->update([
            'clock_out' => now(),
            'photo_out' => $this->storePhoto($photoBase64),
            'lat_out' => $lat,
            'long_out' => $lng,
        ]);
    }

    public function clockOutHalf(User $user, Schedule $schedule, $lat, $lng)
    {
        $this->validateLocation($schedule, $lat, $lng);

        $attendance = Attendance::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            throw ValidationException::withMessages(['attendance' => 'Data absen masuk tidak ditemukan.']);
        }

        $attendance->update([
            'status' => 'setengah',
            'clock_out' => now(),
        ]);
    }

    public function permit(User $user, Schedule $schedule)
    {
        Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'date' => today(),
            ],
            ['status' => 'izin']
        );
    }
    

    // --- HELPER FUNCTIONS (Private) ---

 

    private function storePhoto($base64)
    {
        if (!$base64) return null;

        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        $path = 'attendance/' . uniqid() . '.jpg';
        
        Storage::disk('public')->put($path, $data);
        
        return $path;
    }

    public function permitWithReason(
    User $user,
    Schedule $schedule,
    string $type,
    ?string $reason
) {
    if (!in_array($type, ['izin', 'sakit'])) {
        throw ValidationException::withMessages([
            'type' => 'Jenis izin tidak valid.'
        ]);
    }

    Attendance::updateOrCreate(
        [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'date' => today(),
        ],
        [
            'status' => $type,
            'reason' => $reason,
        ]
    );
}

}
