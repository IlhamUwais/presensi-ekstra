<?php

namespace App\Filament\Resources\RoomEkstras;

use App\Filament\Resources\RoomEkstras\Pages\CreateRoomEkstra;
use App\Filament\Resources\RoomEkstras\Pages\EditRoomEkstra;
use App\Filament\Resources\RoomEkstras\Pages\ListRoomEkstras;
use App\Filament\Resources\RoomEkstras\Schemas\RoomEkstraForm;
use App\Filament\Resources\RoomEkstras\Tables\RoomEkstrasTable;
use App\Models\RoomEkstra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoomEkstraResource extends Resource
{
    protected static ?string $model = RoomEkstra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RoomEkstra';
        protected static ?string $navigationLabel = 'Kelola Ruangan Ekstra';

    public static function form(Schema $schema): Schema
    {
        return RoomEkstraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomEkstrasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoomEkstras::route('/'),
            'create' => CreateRoomEkstra::route('/create'),
            'edit' => EditRoomEkstra::route('/{record}/edit'),
        ];
    }
}
