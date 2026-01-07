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
use App\Services\AttendanceService;
use Illuminate\Validation\ValidationException;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class PresensiPage extends Page
{
    protected string $view = 'filament.siswa.pages.presensi';
    protected static ?string $navigationLabel = 'Kelola Absen  ';
    public $schedules;
    public $photoMasuk;
    public $photoPulang;
    public $userLat; 
    public $userLng; 

    public function mount()
    {
        $today = Carbon::now()->locale('id')->dayName;

        $this->schedules = Schedule::where('day_of_week', $today)
            ->whereHas(
                'ekstra.members',
                fn($q) =>
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

    public function savePhotoMasuk($p)
    {
        $this->photoMasuk = $p;
    }
    public function savePhotoPulang($p)
    {
        $this->photoPulang = $p;
    }

    /* =======================
        ACTIONS
    ======================= */

    public function absenMasuk(Schedule $s)
    {
        try {
            app(AttendanceService::class)->clockIn(
                Auth::user(),
                $s,
                $this->userLat, // (KIRIM DATA) Koordinat user dikirim ke Service...
                $this->userLng, // (KIRIM DATA) ...untuk dihitung jaraknya (Validasi Radius) melawan koordinat Lokasi Ekstra.
                $this->photoMasuk
            );

            $this->photoMasuk = null;
            Notification::make()->success()->title('Absen masuk berhasil')->send();
        } catch (ValidationException $e) {
            Notification::make()->danger()->title('Gagal Absen')->body($e->getMessage())->send();
        }
    }

    public function absenSetengah(Schedule $s)
    {
        try {
            app(AttendanceService::class)->clockOutHalf(
                Auth::user(),
                $s,
                $this->userLat,
                $this->userLng
            );

            Notification::make()->warning()->title('Absen setengah dicatat')->send();
        } catch (ValidationException $e) {
            Notification::make()->danger()->title('Gagal Absen')->body($e->getMessage())->send();
        }
    }

    public function absenPulang(Schedule $s)
    {
        try {
            app(AttendanceService::class)->clockOut(
                Auth::user(),
                $s,
                $this->userLat,
                $this->userLng,
                $this->photoPulang
            );

            $this->photoPulang = null;
            Notification::make()->success()->title('Absen pulang berhasil')->send();
        } catch (ValidationException $e) {
            Notification::make()->danger()->title('Gagal Absen')->body($e->getMessage())->send();
        }
    }

    public function izinAction(): Action
    {
        return Action::make('izinAction')
            ->label('Izin / Sakit')
            ->modalHeading('Form Izin Presensi')
            ->modalSubmitActionLabel('Kirim Izin')
            ->schema([
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ])
                    ->required(),
                 
                Textarea::make('reason')
                    ->label('Alasan')
                    ->required()
                    ->rows(3),
            ])
            ->action(function (array $data, array $arguments) {
                $schedule = Schedule::find($arguments['schedule_id']);
                if (!$schedule) return;

                app(AttendanceService::class)->permitWithReason(Auth::user(), $schedule, $data['status'], $data['reason']);

                Notification::make()->success()->title('Status izin berhasil dicatat')->send();
            });
    }
}
