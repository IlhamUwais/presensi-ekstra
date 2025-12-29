<?php

namespace App\Filament\Resources\Ekstrakulikulers\Pages;

use App\Filament\Resources\Ekstrakulikulers\EkstrakulikulerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEkstrakulikuler extends EditRecord
{
    protected static string $resource = EkstrakulikulerResource::class;

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
