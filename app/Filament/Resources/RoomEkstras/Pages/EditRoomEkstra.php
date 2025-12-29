<?php

namespace App\Filament\Resources\RoomEkstras\Pages;

use App\Filament\Resources\RoomEkstras\RoomEkstraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomEkstra extends EditRecord
{
    protected static string $resource = RoomEkstraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
        public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
