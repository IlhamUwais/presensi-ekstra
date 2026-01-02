<?php

namespace App\Filament\Pembina\Resources\Kehadirans\Pages;

use App\Filament\Pembina\Resources\Kehadirans\KehadiranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKehadirans extends ListRecords
{
    protected static string $resource = KehadiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
