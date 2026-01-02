<?php

namespace App\Filament\Pembina\Resources\Kehadirans;

use App\Filament\Pembina\Resources\Kehadirans\Pages\CreateKehadiran;
use App\Filament\Pembina\Resources\Kehadirans\Pages\EditKehadiran;
use App\Filament\Pembina\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\Pembina\Resources\Kehadirans\Schemas\KehadiranForm;
use App\Filament\Pembina\Resources\Kehadirans\Tables\KehadiransTable;
use App\Models\Attendance;
use App\Models\Kehadiran;
use App\Models\Schedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KehadiranResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Schedule';

    public static function form(Schema $schema): Schema
    {
        return KehadiranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table);
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
            'index' => ListKehadirans::route('/'),
            // 'create' => CreateKehadiran::route('/create'),
            'edit' => EditKehadiran::route('/{record}/edit'),
        ];
    }
}
