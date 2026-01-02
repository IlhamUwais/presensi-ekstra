<?php

namespace App\Filament\Pembina\Resources\Administrasis;

use App\Filament\Pembina\Resources\Administrasis\Pages\CreateAdministrasi;
use App\Filament\Pembina\Resources\Administrasis\Pages\EditAdministrasi;
use App\Filament\Pembina\Resources\Administrasis\Pages\ListAdministrasis;
use App\Filament\Pembina\Resources\Administrasis\Schemas\AdministrasiForm;
use App\Filament\Pembina\Resources\Administrasis\Tables\AdministrasisTable;
use App\Models\Administrasi;
use App\Models\MemberEkstra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdministrasiResource extends Resource
{
    protected static ?string $model = MemberEkstra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MemberEkstra';

    public static function form(Schema $schema): Schema
    {
        return AdministrasiForm::configure($schema);
    }
 
    public static function table(Table $table): Table
    {
        return AdministrasisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdministrasis::route('/'),
            'create' => CreateAdministrasi::route('/create'),
            'edit' => EditAdministrasi::route('/{record}/edit'),
        ];
    }
}
