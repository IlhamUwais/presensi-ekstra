<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schedule_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('clock_in')
                    ->time()
                    ->sortable(),
                TextColumn::make('clock_out')
                    ->time()
                    ->sortable(),
                TextColumn::make('photo_in')
                    ->searchable(),
                TextColumn::make('photo_out')
                    ->searchable(),
                TextColumn::make('lat_in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('long_in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lat_out')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('long_out')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
