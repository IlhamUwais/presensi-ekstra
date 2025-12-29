<?php

namespace App\Filament\Resources\MemberEkstras\Pages;

use App\Filament\Resources\MemberEkstras\MemberEkstraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMemberEkstra extends EditRecord
{
    protected static string $resource = MemberEkstraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
