<?php

namespace App\Filament\Resources\Ekstrakulikulers\Schemas;

use Closure;
use App\Models\Schedule;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Utilities\Get;

class EkstrakulikulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ekstrakurikuler')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Ekstrakurikuler')
                                    ->required(),

                                Select::make('room_ekstra_id')
                                    ->label('Lokasi / Titik Kumpul')
                                    ->relationship('roomEkstra', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live(), // Wajib Live agar Repeater di bawah bisa baca perubahannya
                            ]),

                        Select::make('pembina_id')
                            ->label('Guru Pembina')
                            ->relationship(
                                name: 'pembina',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('role', 'pembina')
                            )
                            ->searchable()
                            ->live()
                            ->preload()
                            ->required()
                            ->unique(modifyRuleUsing: function (Unique $rule, $record) {
                                return $record ? $rule->ignore($record->id) : $rule;
                            })
                            ->validationMessages([
                                'unique' => 'Guru ini sudah memegang ekstra lain.',
                            ]),

                        // --- REPEATER JADWAL ---
                        Repeater::make('schedules')
                            ->relationship()
                            ->label('Jadwal Latihan')
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Hari Latihan')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('day_of_week')
                                            ->label('Hari')
                                            ->options([
                                                'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu',
                                                'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu'
                                            ])
                                            ->required()
                                            ->live()
                                            ->native(false),

                                        TimePicker::make('start_time')
                                            ->label('Jam Mulai')
                                            ->seconds(false)
                                            ->required()
                                            ->live(),

                                        TimePicker::make('end_time')
                                            ->label('Jam Selesai')
                                            ->seconds(false)
                                            ->required()
                                            ->after('start_time')
                                            // LOGIC VALIDASI BENTROK
                                           ->rules([
                                                    // FIX: Ganti '$parentRecord' jadi '$record' agar Filament membacanya
                                                fn (Get $get, $record) => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                                    
                                                    // 1. Ambil Room ID (Naik level path)
                                                    $roomId = $get('../../room_ekstra_id');
                                                    // Fallback level path (jaga-jaga)
                                                    if (!$roomId) $roomId = $get('../../../room_ekstra_id');

                                                    $hari = $get('day_of_week');
                                                    $jamMulai = $get('start_time');
                                                    $jamSelesai = $value;

                                                    // Validasi kelengkapan data
                                                    if (!$roomId) {
                                                        $fail('Lokasi belum terbaca. Coba pilih ulang Lokasi Ekstra di atas.');
                                                        return;
                                                    }
                                                    if (!$hari || !$jamMulai || !$jamSelesai) return;

                                                    // 2. Query Cek Bentrok
                                                    $bentrok = Schedule::query()
                                                        ->whereHas('ekstra', function ($query) use ($roomId, $record) {
                                                            $query->where('room_ekstra_id', $roomId);
                                                            
                                                            // LOGIC PENTING: PENGECUALIAN DIRI SENDIRI
                                                            // Kita pakai '$record' (bawaan Filament)
                                                            if ($record) {
                                                                $query->where('id', '!=', $record->id);
                                                            }
                                                        })
                                                        ->where('day_of_week', $hari)
                                                        ->where(function ($q) use ($jamMulai, $jamSelesai) {
                                                            // Rumus Tabrakan: (Start < End_Lama) AND (End > Start_Lama)
                                                            $q->where('start_time', '<', $jamSelesai)
                                                            ->where('end_time', '>', $jamMulai);
                                                        })
                                                        ->with('ekstra')
                                                        ->first();

                                                    // 3. Tampilkan Error
                                                    if ($bentrok) {
                                                        $nama = $bentrok->ekstra->name ?? 'Ekstra Lain';
                                                        // Debugging: Pastikan namanya bukan nama diri sendiri
                                                        $fail("Bentrok! Ruangan dipakai '{$nama}' pada {$bentrok->day_of_week} jam {$bentrok->start_time}-{$bentrok->end_time}.");
                                                    }
                                                },
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}