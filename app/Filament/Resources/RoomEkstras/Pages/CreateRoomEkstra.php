<?php

namespace App\Filament\Resources\RoomEkstras\Pages;

use App\Filament\Resources\RoomEkstras\RoomEkstraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoomEkstra extends CreateRecord
{
    protected static string $resource = RoomEkstraResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
