<?php

namespace App\Filament\Pembina\Resources\Kehadirans\Pages;

use App\Filament\Pembina\Resources\Kehadirans\KehadiranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKehadiran extends EditRecord
{
    protected static string $resource = KehadiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
