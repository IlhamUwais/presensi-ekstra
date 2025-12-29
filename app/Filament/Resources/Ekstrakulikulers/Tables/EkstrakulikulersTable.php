<?php

namespace App\Filament\Resources\Ekstrakulikulers\Tables;

use Dom\Text;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class EkstrakulikulersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Ekstra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roomEkstra.name') // Relasi ke Lokasi
                    ->label('Lokasi')
                    ->searchable()
                    // ->sortable()
                    ->placeholder('Belum diatur'),

                TextColumn::make('pembina.name') // Relasi ke User Pembina
                    ->label('Pembina')
                    ->searchable()
                    ->placeholder('Belum ada'),
                  
            ])
            ->filters([
                
                SelectFilter::make('room_ekstra_id')
                    ->label('Lokasi')
                    ->relationship('roomEkstra', 'name'),
            ])
            ->actions([

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}