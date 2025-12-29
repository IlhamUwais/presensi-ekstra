<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendanceChart extends ChartWidget
{
    // Judul Grafik
    protected  ?string $heading = 'Tren Kehadiran (7 Hari Terakhir)';
    
    // Urutan posisi widget (biar ada di tengah/bawah stats)
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // 1. Ambil data & kelompokkan per hari
        $data = Trend::model(Attendance::class)
            ->between(
                start: now()->subDays(7), // Mulai dari 7 hari lalu
                end: now(),               // Sampai hari ini
            )
            ->perDay()     // Kelompokkan per hari
            ->count();     // Hitung jumlahnya

        // 2. Format data supaya dimengerti Chart.js
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa Hadir',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10B981', // Warna garis (Hijau Emerald)
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)', // Warna arsir bawah garis
                    'fill' => true, // Area bawah garis diwarnai
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Bisa diganti 'bar' kalau mau grafik batang
    }
}