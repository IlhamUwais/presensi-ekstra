<?php

namespace App\Filament\Resources\RoomEkstras\Pages;

use App\Filament\Resources\RoomEkstras\RoomEkstraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomEkstras extends ListRecords
{
    protected static string $resource = RoomEkstraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
