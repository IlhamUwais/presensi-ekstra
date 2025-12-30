<?php

namespace App\Filament\Siswa\Resources;

use BackedEnum;
use Filament\Tables;
use Filament\Tables\Table;
// GANTI Form MENJADI Schema (Sesuai Versi Filament Kamu)
use App\Models\MemberEkstra;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Ekstrakulikuler;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use App\Filament\Siswa\Resources\EkskulResource\Pages;
use App\Services\EkstraRegistrationService;
use Illuminate\Validation\ValidationException;

class EkskulResource extends Resource
{
    protected static ?string $model = Ekstrakulikuler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Daftar Ekstrakurikuler';

    protected static ?string $recordTitleAttribute = 'name';

    // --- PERBAIKAN UTAMA DI SINI ---
    // Menggunakan Schema sesuai error log kamu
    public static function form(Schema $schema): Schema
    {
        // Di versi Schema, biasanya menggunakan ->components([]) atau tetap ->schema([])
        // Kita kosongkan saja array-nya
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Ekskul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('pembina.name')
                    ->label('Pembina')
                    ->icon('heroicon-m-user')
                    ->default('-'),
                TextColumn::make('schedules.day_of_week')
                    ->label('Hari Ekstra')
                    ->icon('heroicon-m-user')
                    ->default('-'),
                TextColumn::make('schedules.start_time')
                    ->label('Waktu Mulai')
                    ->icon('heroicon-m-user')
                    ->default('-'),
                TextColumn::make('schedules.end_time')
                    ->label('Waktu Selesai ')
                    ->icon('heroicon-m-user')
                    ->default('-'),
                TextColumn::make('roomEkstra.name')
                    ->label('Lokasi')
                    ->icon('heroicon-m-user')
                    ->default('-'),



                TextColumn::make('status_saya')
                    ->label('Status Anda')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $userId = Auth::id();
                        $member = MemberEkstra::where('user_id', $userId)
                            ->where('ekstrakulikuler_id', $record->id)
                            ->first();

                        return $member ? strtoupper($member->status ?? 'PENDING') : 'BELUM DAFTAR';
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'APPROVED',
                        'warning' => fn($state) => $state === 'PENDING',
                        'danger'  => fn($state) => $state === 'REJECTED',
                        'gray'    => 'BELUM DAFTAR',
                    ]),
            ])
            ->actions([
                // Tombol Daftar (Menggunakan Full Path biar aman dari error import)
                Action::make('daftar')
                    ->label('Gabung')
                    ->icon('heroicon-m-plus-circle')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Daftar Ekstrakurikuler')
                    ->modalDescription('Yakin ingin bergabung?')
                    ->modalSubmitActionLabel('Ya, Daftar')
                    ->hidden(function (Ekstrakulikuler $record) {
                        return MemberEkstra::where('user_id', Auth::id())
                            ->where('ekstrakulikuler_id', $record->id)
                            ->exists();
                    })
                    ->action(function (Ekstrakulikuler $record) {
                        try {
                            // Panggil Service untuk validasi jadwal bentrok & daftar
                            app(EkstraRegistrationService::class)->register(Auth::user(), $record->id);

                            Notification::make()
                                ->title('Berhasil Mendaftar')
                                ->success()
                                ->send();
                        } catch (ValidationException $e) {
                            Notification::make()
                                ->title('Gagal Mendaftar')
                                ->body($e->validator->errors()->first())
                                ->danger()
                                ->send();
                        }
                    }),

                // Tombol Info
                Action::make('info_status')
                    ->label(function (Ekstrakulikuler $record) {
                        $member = MemberEkstra::where('user_id', Auth::id())
                            ->where('ekstrakulikuler_id', $record->id)
                            ->first();
                        return $member ? 'Status: ' . ucfirst($member->status ?? 'Pending') : '';
                    })
                    ->disabled()
                    ->color('gray')
                    ->visible(function (Ekstrakulikuler $record) {
                        return MemberEkstra::where('user_id', Auth::id())
                            ->where('ekstrakulikuler_id', $record->id)
                            ->exists();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEkskuls::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
