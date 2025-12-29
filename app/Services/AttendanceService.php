<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceService
{
    public function __construct(
        protected GeoFenceService $geoFence
    ) {}

    protected function getTodayAttendance(int $userId, int $scheduleId): Attendance
    {
        return Attendance::firstOrCreate(
            [
                'user_id'     => $userId,
                'schedule_id' => $scheduleId,
                'date'        => now()->toDateString(),
            ],
            [
                'status' => 'alpha',
            ]
        );
    }

    protected function savePhoto(string $base64): string
    {
        $data = explode(',', $base64)[1];
        $path = 'attendance/'.Str::uuid().'.jpg';
        Storage::disk('public')->put($path, base64_decode($data));
        return $path;
    }

    public function clockIn(int $userId, Schedule $schedule, float $lat, float $lng, string $photo): Attendance
    {
        $room = $schedule->roomEkstra;

        if (!$this->geoFence->isInsideRadius($lat,$lng,$room->latitude,$room->longitude,$room->radius)) {
            throw ValidationException::withMessages(['gps'=>'Di luar area']);
        }

        $attendance = $this->getTodayAttendance($userId,$schedule->id);

        if ($attendance->clock_in) {
            throw ValidationException::withMessages(['clock_in'=>'Sudah absen']);
        }

        $attendance->update([
            'clock_in'=>now(),
            'photo_in'=>$this->savePhoto($photo),
            'lat_in'=>$lat,
            'long_in'=>$lng,
            'status'=>'hadir'
        ]);

        return $attendance;
    }

    public function clockOut(Attendance $attendance, float $lat, float $lng, string $photo): Attendance
    {
        if (! $this->canClockOut($attendance->schedule)) {
            throw ValidationException::withMessages(['clock_out'=>'Belum waktunya pulang']);
        }

        $attendance->update([
            'clock_out'=>now(),
            'photo_out'=>$this->savePhoto($photo),
            'lat_out'=>$lat,
            'long_out'=>$lng,
        ]);

        return $attendance;
    }

    protected function canClockOut(Schedule $schedule): bool
    {
        return now()->greaterThanOrEqualTo(
            Carbon::createFromFormat('H:i:s',$schedule->end_time)->subMinutes(30)
        );
    }
      public function izinAtauSakit(
        int $userId,
        Schedule $schedule,
        string $status,
        string $reason
    ): Attendance {
        if (! in_array($status, ['izin', 'sakit'])) {
            throw ValidationException::withMessages([
                'status' => 'Status tidak valid.',
            ]);
        }

        $attendance = $this->getTodayAttendance($userId, $schedule->id);

        $attendance->update([
            'status' => $status,
            'reason' => $reason,
        ]);

        return $attendance;
    }

    public function getAvailableActions(Attendance $attendance, Schedule $schedule): array
    {
        if (! $attendance->clock_in) return ['clock_in','izin'];

        if ($attendance->clock_in && ! $attendance->clock_out) {
            return $this->canClockOut($schedule)
                ? ['clock_out']
                : ['setengah'];
        }

        return [];
    }

    public function setengah(int $userId, Schedule $schedule, string $reason): Attendance
    {
        $attendance = $this->getTodayAttendance($userId,$schedule->id);

        if (! $attendance->clock_in) {
            throw ValidationException::withMessages(['setengah'=>'Belum masuk']);
        }

        $attendance->update([
            'status'=>'setengah',
            'reason'=>$reason
        ]);

        return $attendance;
    }
}
