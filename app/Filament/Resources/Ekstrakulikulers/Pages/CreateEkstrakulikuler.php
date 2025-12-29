<?php

namespace App\Filament\Resources\Ekstrakulikulers\Pages;

use App\Filament\Resources\Ekstrakulikulers\EkstrakulikulerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEkstrakulikuler extends CreateRecord
{
    protected static string $resource = EkstrakulikulerResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
