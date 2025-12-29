<?php

namespace App\Filament\Resources\MemberEkstras;

use App\Filament\Resources\MemberEkstras\Pages\CreateMemberEkstra;
use App\Filament\Resources\MemberEkstras\Pages\EditMemberEkstra;
use App\Filament\Resources\MemberEkstras\Pages\ListMemberEkstras;
use App\Filament\Resources\MemberEkstras\Schemas\MemberEkstraForm;
use App\Filament\Resources\MemberEkstras\Tables\MemberEkstrasTable;
use App\Models\MemberEkstra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;



class MemberEkstraResource extends Resource
{
    protected static ?string $model = MemberEkstra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MemberEkstra';
        protected static ?string $navigationLabel = 'Kelola Member ekstra';

    public static function form(Schema $schema): Schema
    {
        return MemberEkstraForm::configure($schema);
    }

 public static function table(Table $table): Table
    {
        return MemberEkstrasTable::configure($table);
    }

    public static function canViewAny(): bool
{
    $user = Filament::auth()->user();

    return $user && in_array($user->role, ['admin', 'pembina']);
}


   public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $user = Filament::auth()->user();

    if ($user && $user->role === 'pembina') {
        $query->whereHas('ekstra', fn (Builder $q) =>
            $q->where('pembina_id', $user->id)
        );
    }

    return $query;
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
            'index' => ListMemberEkstras::route('/'),
            'create' => CreateMemberEkstra::route('/create'),
            'edit' => EditMemberEkstra::route('/{record}/edit'),
        ];
    }
}
