<?php

namespace App\Filament\Pembina\Resources\Administrasis\Pages;

use App\Filament\Pembina\Resources\Administrasis\AdministrasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdministrasis extends ListRecords
{
    protected static string $resource = AdministrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
