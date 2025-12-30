<?php

namespace App\Filament\Siswa\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Services\GeoFenceService;

class PresensiPage extends Page
{
    protected string $view = 'filament.siswa.pages.presensi';

    public $schedules;
    public $photoMasuk;
    public $photoPulang;
    public $userLat;
    public $userLng;

    public function mount()
    {
        $today = Carbon::now()->locale('id')->dayName;

        $this->schedules = Schedule::where('day_of_week', $today)
            ->whereHas('ekstra.members', fn ($q) =>
                $q->where('user_id', Auth::id())
                  ->where('status', 'approved')
                  ->where('is_active', 1)
            )
            ->with(['ekstra', 'roomEkstra'])
            ->get();
    }

    /* =======================
        UTIL
    ======================= */

    public function getTodayAttendance(Schedule $schedule)
    {
        return Attendance::whereDate('date', today())
            ->where('schedule_id', $schedule->id)
            ->where('user_id', Auth::id())
            ->first();
    }

    private function validateLocation(Schedule $schedule): bool
    {
        if (empty($this->userLat) || empty($this->userLng)) {
            Notification::make()->danger()
                ->title('GPS belum aktif')
                ->send();
            return false;
        }

        $room = $schedule->roomEkstra;
        if (!$room || !$room->latitude || !$room->longitude) return true;

        return app(GeoFenceService::class)->isInsideRadius(
            $this->userLat,
            $this->userLng,
            $room->latitude,
            $room->longitude,
            $room->radius ?? 50
        );
    }

    /* =======================
        LOGIC WAKTU
    ======================= */

    public function canAbsenMasuk(Schedule $s): bool
    {
        $a = $this->getTodayAttendance($s);
        if ($a && ($a->clock_in || $a->status !== 'hadir')) return false;

        return now()->greaterThanOrEqualTo(
            Carbon::parse($s->start_time)->subMinutes(30)
        );
    }

    public function canAbsenSetengah(Schedule $s): bool
    {
        $a = $this->getTodayAttendance($s);
        if (!$a || !$a->clock_in || $a->clock_out || $a->status === 'setengah') return false;

        return now()->between(
            Carbon::parse($s->start_time),
            Carbon::parse($s->end_time)->subMinutes(30)
        );
    }

    public function canAbsenPulang(Schedule $s): bool
    {
        $a = $this->getTodayAttendance($s);
        if (!$a || !$a->clock_in || $a->clock_out || $a->status === 'setengah') return false;

        return now()->greaterThanOrEqualTo(
            Carbon::parse($s->end_time)->subMinutes(30)
        );
    }

    public function canIzin(Schedule $s): bool
    {
        $a = $this->getTodayAttendance($s);
        return !$a;
    }

    /* =======================
        PHOTO
    ======================= */

    private function storePhoto($base64)
    {
        if (!$base64) return null;

        $data = base64_decode(
            preg_replace('#^data:image/\w+;base64,#i', '', $base64)
        );

        $path = 'attendance/' . uniqid() . '.jpg';
        Storage::disk('public')->put($path, $data);
        return $path;
    }

    public function savePhotoMasuk($p) { $this->photoMasuk = $p; }
    public function savePhotoPulang($p) { $this->photoPulang = $p; }

    /* =======================
        ACTIONS
    ======================= */

    public function absenMasuk(Schedule $s)
    {
        if (!$this->validateLocation($s) || !$this->photoMasuk) return;

        $a = Attendance::firstOrCreate([
            'user_id' => Auth::id(),
            'schedule_id' => $s->id,
            'date' => today(),
        ]);

        $a->update([
            'clock_in' => now(),
            'photo_in' => $this->storePhoto($this->photoMasuk),
            'lat_in' => $this->userLat,
            'long_in' => $this->userLng,
            'status' => 'hadir'
        ]);

        $this->photoMasuk = null;

        Notification::make()->success()
            ->title('Absen masuk berhasil')
            ->send();
    }

    public function absenSetengah(Schedule $s)
    {
        if (!$this->validateLocation($s)) return;

        $this->getTodayAttendance($s)->update([
            'status' => 'setengah',
            'clock_out' => now(),
        ]);

        Notification::make()->warning()
            ->title('Absen setengah dicatat')
            ->send();
    }

    public function absenPulang(Schedule $s)
    {
        if (!$this->validateLocation($s) || !$this->photoPulang) return;

        $this->getTodayAttendance($s)->update([
            'clock_out' => now(),
            'photo_out' => $this->storePhoto($this->photoPulang),
            'lat_out' => $this->userLat,
            'long_out' => $this->userLng,
        ]);

        $this->photoPulang = null;

        Notification::make()->success()
            ->title('Absen pulang berhasil')
            ->send();
    }

    public function izin(Schedule $s)
    {
        Attendance::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'schedule_id' => $s->id,
                'date' => today(),
            ],
            ['status' => 'izin']
        );

        Notification::make()->success()
            ->title('Izin dicatat')
            ->send();
    }
}
