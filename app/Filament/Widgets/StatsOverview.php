<?php

namespace App\Filament\Widgets;

use App\Models\User; // <--- Ganti ini (Bukan Student)
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
   protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            // KARTU 1: Total Siswa
            // Kita hitung User yang kolom 'role'-nya adalah 'siswa'
            Stat::make('Total Siswa', User::where('role', 'siswa')->count()) 
                ->description('Siswa terdaftar aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            // KARTU 2: Hadir Hari Ini
            Stat::make('Hadir Hari Ini', Attendance::whereDate('created_at', today())->count())
                ->description('Siswa sudah scan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            // KARTU 3: Terlambat
            Stat::make('Izin/sakit', Attendance::whereDate('created_at', today())->where('status', ['izin', 'sakit'])->count())
                ->description('Perlu perhatian')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'), 
            
            // Kartu 4 opsional (bisa dihapus atau diganti query lain)
        ];
    }
}