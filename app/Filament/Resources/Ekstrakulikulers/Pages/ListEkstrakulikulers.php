<?php

namespace App\Filament\Resources\Ekstrakulikulers\Pages;

use App\Filament\Resources\Ekstrakulikulers\EkstrakulikulerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEkstrakulikulers extends ListRecords
{
    protected static string $resource = EkstrakulikulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
        public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
