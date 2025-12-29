<?php

namespace App\Filament\Resources\RoomEkstras\Schemas;

use Filament\Schemas\Schema;
use Dotswan\MapPicker\Fields\Map;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class RoomEkstraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ruangan')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Ruangan / Titik Kumpul')
                            ->required(),
                        
                        TextInput::make('location')
                            ->label('Deskripsi Lokasi')
                            ->placeholder('Contoh: Depan Tiang Bendera'),
                            
                    ]),

                Section::make('Titik Koordinat (Geofencing)')
                    ->description('Tentukan titik pusat lokasi dan jarak radius absen.')
                    ->schema([
                        
                        // --- MAP PICKER ---
                        Map::make('location_map')
                            ->label('Pilih Lokasi (Satelit + Nama)')
                            ->columnSpanFull()
                            
                            // 1. Lokasi Default (Magelang)
                            ->defaultLocation(-7.4726, 110.2198)

                            // 2. LINK GOOGLE HYBRID (Satelit + Jalan)
                            // Ini satu-satunya cara biar muncul nama jalan di mode satelit
                            ->tilesUrl('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}')
                            
                            // 3. Setting Standar (Tanpa kode aneh-aneh)
                            ->zoom(18)           
                            ->draggable()       
                            ->dehydrated(false)  
                            
                            // 4. Logic Load Data (Edit Mode)
                            ->afterStateHydrated(function ($state, $record, callable $set) {
                                if ($record) {
                                    $set('location_map', [
                                        'lat' => (float) $record->latitude,
                                        'lng' => (float) $record->longitude,
                                    ]);
                                }
                            })
                            // 5. Logic Update Input (Saat marker digeser)
                            ->afterStateUpdated(function (callable $set, array $state) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                                
                                
                            }),

                        // --- INPUT KOORDINAT ---
                        Grid::make(3)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude (Lintang)')
                                    ->required()
                                    ->readOnly(), 
                                
                                TextInput::make('longitude')
                                    ->label('Longitude (Bujur)')
                                    ->required()
                                    ->readOnly(),

                                TextInput::make('radius')
                                    ->label('Radius (Meter)')
                                    ->numeric()
                                    ->default(20)
                                    ->required(),
                                    
                                    
                            ]),
                    ]),
            ]);
    }
}