<?php

namespace App\Services;

use App\Models\User;
use App\Models\Schedule;
use App\Models\MemberEkstra;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EkstraRegistrationService
{
    public function register(User $user, int $ekstraId): void
    {
        // 1. Cegah daftar ulang
        $exists = MemberEkstra::where('user_id', $user->id)
            ->where('ekstrakulikuler_id', $ekstraId)
            ->where('is_active', true)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'ekstra' => 'Kamu sudah terdaftar atau menunggu persetujuan di ekstra ini.',
            ]);
        }

        // 2. Ambil semua jadwal ekstra yang mau didaftari
        $newSchedules = Schedule::where('ekstrakulikuler_id', $ekstraId)->get();

        // 3. Ambil jadwal ekstra yang SUDAH APPROVED
        $approvedSchedules = Schedule::whereHas('ekstra.members', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('status', 'approved')
              ->where('is_active', true);
        })->get();

        // 4. Cek tabrakan jadwal
        foreach ($newSchedules as $new) {
            foreach ($approvedSchedules as $old) {
                if (
                    $new->day_of_week === $old->day_of_week &&
                    $new->start_time < $old->end_time &&
                    $new->end_time > $old->start_time
                ) {
                    throw ValidationException::withMessages([
                        'schedule' => 'Jadwal ekstrakurikuler bentrok dengan ekstra lain yang sudah kamu ikuti.',
                    ]);
                }
            }
        }

        // 5. Simpan pendaftaran
        DB::transaction(function () use ($user, $ekstraId) {
            MemberEkstra::create([
                'user_id' => $user->id,
                'ekstrakulikuler_id' => $ekstraId,
                'status' => 'pending',
                'is_active' => true,
            ]);
        });
    }
}
