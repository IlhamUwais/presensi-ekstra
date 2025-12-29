<?php

namespace App\Filament\Resources\SchoolClasses\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SchoolClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ,
                Select::make('jenjang_kelas')
                ->options([
                    10 => '10',
                    11 => '11',
                    12 => '12',
                ])
            ]);
    }
}
