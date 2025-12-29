<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LatestAttendance extends TableWidget
{
   public function table(Table $table): Table
{
    return $table
        ->query(
            Attendance::query()->latest()->limit(5) // Ambil 5 data terakhir
        )
        ->columns([
            TextColumn::make('user.name')->label('Nama Siswa'),
            TextColumn::make('created_at')->label('Waktu Masuk')->since(), // Tampil "2 minutes ago"
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'hadir' => 'success',
                    'izin' => 'info',
                    'sakit' => 'warning',
                    'alpha' => 'danger',
                    default => 'gray',
                }),
        ])
        ->paginated(false); // Hilangkan navigasi halaman biar ringkas
}
}
