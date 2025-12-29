<?php

namespace App\Filament\Siswa\Pages;

use Carbon\Carbon;
use App\Models\Schedule;
use Filament\Pages\Page;
use App\Models\Attendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Services\GeoFenceService;



class PresensiPage extends Page
{
    protected string $view = 'filament.siswa.pages.presensi';

    public $schedules;
    public $photo;
    public $userLat;
    public $userLng;
    public $activeScheduleId;

    public function mount()
    {
        $today = Carbon::now()->locale('id')->dayName;

        $this->schedules = Schedule::where('day_of_week', $today)
            ->whereHas('ekstra.members', function ($q) {
                $q->where('user_id', Auth::id())
                  ->where('status', 'approved')
                  ->where('is_active', 1);
            })
            ->with(['ekstra', 'roomEkstra'])
            ->get();
    }

    /* ===============================
        DATA ATTENDANCE
    =============================== */

    private function validateLocation(Schedule $schedule): bool
{
    if (!$this->userLat || !$this->userLng) {
        Notification::make()
            ->danger()
            ->title('Lokasi tidak terdeteksi bos yahaha')
            ->body('Silahkan aktifkan GPS dulu bos yahahah hayukkk')
            ->send();

        return false;
    }

    $room = $schedule->roomEkstra;

    if (!$room || !$room->latitude || !$room->longitude) {
        return true; // fallback jika lokasi belum diset
    }

    $geo = app(GeoFenceService::class);

    $inside = $geo->isInsideRadius(
        $this->userLat,
        $this->userLng,
        $room->latitude,
        $room->longitude,
        $room->radius ?? 50
    );

    if (!$inside) {
        Notification::make()
            ->danger()
            ->title('Di luar area presensi')
            ->body('Anda berada di luar radius lokasi')
            ->send();
    }

    return $inside;
}


    public function getTodayAttendance($schedule)
    {
        return Attendance::whereDate('date', today())
            ->where('schedule_id', $schedule->id)
            ->where('user_id', Auth::id())
            ->first();
    }

    /* ===============================
        LOGIC WAKTU
    =============================== */

    // PENTING: Method harus PUBLIC agar bisa diakses dari Blade
    public function canAbsenMasuk(Schedule $schedule): bool
    {
        $attendance = $this->getTodayAttendance($schedule);

        // Sudah absen masuk â†’ tidak boleh absen masuk lagi
        if ($attendance && $attendance->clock_in) {
            return false;
        }

        // Sudah izin â†’ tidak boleh absen
        if ($attendance && $attendance->status === 'izin') {
            return false;
        }

        $now = now();

        // Parse waktu mulai dan kurangi 30 menit
        $startTime = Carbon::parse($schedule->start_time)->subMinutes(30);
        
        // Untuk debugging
        Log::info('Absen Masuk Check:', [
            'now' => $now->format('H:i:s'),
            'start_time' => $schedule->start_time,
            'allowed_from' => $startTime->format('H:i:s'),
            'can_absen' => $now->format('H:i:s') >= $startTime->format('H:i:s')
        ]);

        // Cek apakah waktu sekarang sudah >= waktu mulai dikurangi 30 menit
        return $now->format('H:i:s') >= $startTime->format('H:i:s');
    }

    public function canAbsenPulang(Schedule $schedule): bool
    {
        $attendance = $this->getTodayAttendance($schedule);

        // Belum absen masuk
        if (!$attendance || !$attendance->clock_in) {
            return false;
        }

        // Sudah absen pulang
        if ($attendance->clock_out) {
            return false;
        }

        $now = now();

        // Parse waktu selesai dan kurangi 30 menit
        $endTime = Carbon::parse($schedule->end_time)->subMinutes(30);
        
        // Untuk debugging
        Log::info('Absen Pulang Check:', [
            'now' => $now->format('H:i:s'),
            'end_time' => $schedule->end_time,
            'allowed_from' => $endTime->format('H:i:s'),
            'can_absen' => $now->format('H:i:s') >= $endTime->format('H:i:s')
        ]);

        // Cek apakah waktu sekarang sudah >= waktu selesai dikurangi 30 menit
        return $now->format('H:i:s') >= $endTime->format('H:i:s');
    }

    public function canIzin(Schedule $schedule): bool
    {
        $attendance = $this->getTodayAttendance($schedule);
        
        // Tidak bisa izin kalau sudah absen masuk atau pulang
        if ($attendance && ($attendance->clock_in || $attendance->clock_out)) {
            return false;
        }
        
        return true;
    }

    public function canSetengah(Schedule $schedule): bool
    {
        $attendance = $this->getTodayAttendance($schedule);
        
       
        if ($attendance && $attendance->clock_in) {
            return false;
        }
        
        return true;
    }

    /* ===============================
        CAMERA
    =============================== */

    public function savePhoto($base64)
    {
        $this->photo = $base64;
    }

    private function storePhoto()
    {
        if (!$this->photo) return null;

        $data = base64_decode(str_replace(
            ['data:image/jpeg;base64,', ' '],
            ['', '+'],
            $this->photo
        ));

        $path = 'attendance/' . uniqid() . '.jpg';
        Storage::disk('public')->put($path, $data);

        return $path;
    }

    /* ===============================
        AKSI
    =============================== */

    public function absenMasuk(Schedule $schedule)
    {
        if (!$this->validateLocation($schedule)) {
            return;
        }

        if (!$this->canAbsenMasuk($schedule)) {
            Notification::make()
                ->danger()
                ->title('Tidak bisa absen masuk')
                ->body('Belum waktunya absen atau Anda sudah absen masuk.')
                ->send();
            return;
        }

        if (!$this->photo) {
            Notification::make()
                ->warning()
                ->title('Foto diperlukan')
                ->body('Silakan ambil foto terlebih dahulu.')
                ->send();
            return;
        }

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'date' => today(),
            ],
            ['status' => 'hadir']
        );

        $attendance->update([
            'clock_in' => now(),
            'photo_in' => $this->storePhoto(),
            'status' => 'hadir'
        ]);

        $this->photo = null; // Reset foto setelah berhasil

        Notification::make()
            ->success()
            ->title('Absen masuk berhasil')
            ->send();
            
        // Refresh data
        $this->mount();
    }

    public function absenPulang(Schedule $schedule)
    {
        if (!$this->validateLocation($schedule)) {
            return;
        }

        if (!$this->canAbsenPulang($schedule)) {
            Notification::make()
                ->warning()
                ->title('Tidak bisa absen pulang')
                ->body('Belum waktunya absen pulang atau Anda belum absen masuk.')
                ->send();
            return;
        }

        if (!$this->photo) {
            Notification::make()
                ->warning()
                ->title('Foto diperlukan')
                ->body('Silakan ambil foto terlebih dahulu.')
                ->send();
            return;
        }

        $attendance = $this->getTodayAttendance($schedule);

        $attendance->update([
            'clock_out' => now(),
            'photo_out' => $this->storePhoto(),
        ]);

        $this->photo = null; // Reset foto setelah berhasil

        Notification::make()
            ->success()
            ->title('Absen pulang berhasil')
            ->send();
            
        // Refresh data
        $this->mount();
    }

    public function izin(Schedule $schedule)
    {
        if (!$this->canIzin($schedule)) {
            Notification::make()
                ->warning()
                ->title('Tidak bisa izin')
                ->body('Anda sudah melakukan absensi.')
                ->send();
            return;
        }

        Attendance::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'date' => today(),
            ],
            ['status' => 'izin']
        );

        Notification::make()
            ->success()
            ->title('Izin berhasil dicatat')
            ->send();
            
        // Refresh data
        $this->mount();
    }

    
}