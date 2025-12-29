<?php

namespace App\Filament\Resources\Ekstrakulikulers;

use App\Filament\Resources\Ekstrakulikulers\Pages\CreateEkstrakulikuler;
use App\Filament\Resources\Ekstrakulikulers\Pages\EditEkstrakulikuler;
use App\Filament\Resources\Ekstrakulikulers\Pages\ListEkstrakulikulers;
use App\Filament\Resources\Ekstrakulikulers\Schemas\EkstrakulikulerForm;
use App\Filament\Resources\Ekstrakulikulers\Tables\EkstrakulikulersTable;
use App\Filament\Resources\EkstraResource\Schemas\EkstrakulikulerForm as SchemasEkstrakulikulerForm;
use App\Models\Ekstrakulikuler;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EkstrakulikulerResource extends Resource
{
    protected static ?string $model = Ekstrakulikuler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Ekstrakulikuler';
        protected static ?string $navigationLabel = 'Kelola Ekstrakulikuler';

    public static function form(Schema $schema): Schema
    {
        return EkstrakulikulerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EkstrakulikulersTable::configure($table);
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
            'index' => ListEkstrakulikulers::route('/'),
            'create' => CreateEkstrakulikuler::route('/create'),
            'edit' => EditEkstrakulikuler::route('/{record}/edit'),
        ];
    }
}
