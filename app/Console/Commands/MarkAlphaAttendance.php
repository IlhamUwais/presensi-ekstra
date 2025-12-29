<?php
use Carbon\Carbon;
use App\Services\AttendanceService;

// di dalam handle()
$attendanceService->markAlphaIfMissing(
    $user->id,
    $schedule,
    Carbon::yesterday()
);
