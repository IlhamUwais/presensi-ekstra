<?php

namespace App\Filament\Siswa\Resources;

use BackedEnum;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Siswa\Resources\HistoryAbsensiResource\Pages;


class HistoryAbsensiResource extends Resource
{
    protected static ?string $model = Attendance::class;

     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $navigationLabel = 'Riwayat Absensi';
    
    protected static ?string $pluralModelLabel = 'Riwayat Absensi';

    // Kita matikan fitur Create karena siswa tidak boleh bikin absen manual dari sini
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        // Di versi Schema, biasanya menggunakan ->components([]) atau tetap ->schema([])
        // Kita kosongkan saja array-nya
        return $schema->components([]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            // FILTER PENTING: Hanya tampilkan data milik siswa yang sedang login
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id())->latest('date'))
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),

                // Pastikan di Model Attendance ada relasi: public function schedule() { return $this->belongsTo(Schedule::class); }
                TextColumn::make('schedule.ekstra.name')
                    ->label('Ekstrakulikuler')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('clock_in')
                    ->label('Masuk')
                    ->time('H:i')
                    ->default('-'),

                TextColumn::make('clock_out')
                    ->label('Pulang')
                    ->time('H:i')
                    ->default('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'hadir',
                        'warning' => fn ($state) => in_array($state, ['izin', 'sakit', 'setengah']),
                        'danger' => 'alpha',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistoryAbsensis::route('/'),
        ];
    }
}
