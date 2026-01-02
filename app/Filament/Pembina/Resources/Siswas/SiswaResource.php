<?php

namespace App\Filament\Pembina\Resources\Siswas;

use BackedEnum;
use App\Models\User;
use App\Models\Siswa;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Pembina\Resources\Siswas\Pages\EditSiswa;
use App\Filament\Pembina\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Pembina\Resources\Siswas\Pages\CreateSiswa;
use App\Filament\Pembina\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Pembina\Resources\Siswas\Tables\SiswasTable;

class SiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'User';

    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
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
            'index' => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'edit' => EditSiswa::route('/{record}/edit'),
        ];
    }
}
