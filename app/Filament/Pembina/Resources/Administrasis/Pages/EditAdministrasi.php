<?php

namespace App\Filament\Pembina\Resources\Administrasis\Pages;

use App\Filament\Pembina\Resources\Administrasis\AdministrasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdministrasi extends EditRecord
{
    protected static string $resource = AdministrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
