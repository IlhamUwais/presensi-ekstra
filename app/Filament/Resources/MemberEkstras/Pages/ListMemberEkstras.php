<?php

namespace App\Filament\Resources\MemberEkstras\Pages;

use App\Filament\Resources\MemberEkstras\MemberEkstraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemberEkstras extends ListRecords
{
    protected static string $resource = MemberEkstraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
