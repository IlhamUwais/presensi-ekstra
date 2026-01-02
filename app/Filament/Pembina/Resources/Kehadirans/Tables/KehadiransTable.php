<?php

namespace App\Filament\Pembina\Resources\Kehadirans\Tables;

use Filament\Tables\Table;
use App\Models\Ekstrakulikuler;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class KehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 👇 LOGIC FILTER (PENTING)
            ->modifyQueryUsing(function (Builder $query) {
                $pembinaId = Auth::id();

                // 1. Cari Ekstra milik Pembina ini
                $ekstra = Ekstrakulikuler::where('pembina_id', $pembinaId)->first();

                // 2. Jika pembina tidak punya ekstra, tabel kosong
                if (!$ekstra) {
                    return $query->whereRaw('1 = 0');
                }

                // 3. Filter Data Kehadiran
                // "Ambil data kehadiran DIMANA schedule-nya milik ekstra id ini"
                return $query->whereHas('schedule', function ($subQuery) use ($ekstra) {
                    $subQuery->where('ekstrakulikuler_id', $ekstra->id);
                });
            })
            
            // 👇 TAMPILAN KOLOM YANG LENGKAP
            ->columns([
                // Info Siswa
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('user.nis')
                    ->label('NIS')
                    ->sortable()
                    ->color('gray'),

                // Tanggal & Jadwal
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('schedule.day_of_week') 
                    ->label('Hari Jadwal')
                    ->badge(),

                // Jam Absen
                TextColumn::make('clock_in')
                    ->label('Masuk')
                    ->time('H:i')
                    ->placeholder('-'),

                TextColumn::make('clock_out')
                    ->label('Pulang')
                    ->time('H:i')
                    ->placeholder('-'),

                // Status dengan Warna
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',    // Hijau
                        'izin' => 'warning',     // Kuning
                        'sakit' => 'warning',    // Kuning
                        'alpha' => 'danger',     // Merah
                        'setengah' => 'info',    // Biru (Contoh status lain)
                        default => 'gray',
                    }),


            ])
            
          
            ->filters([
                // Filter berdasarkan Status
                SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ]),
                
                // Filter berdasarkan Tanggal
                Filter::make('date')
                    ->schema([
                        DatePicker::make('dari_tanggal'),
                        DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date) => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date) => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                // Biasanya kehadiran tidak diedit manual, tapi kalau mau diaktifkan silakan
                // EditAction::make(), 
            ])
            ->bulkActions([
                // Filament\Actions\DeleteBulkAction::make(),
            ]);
    }
}