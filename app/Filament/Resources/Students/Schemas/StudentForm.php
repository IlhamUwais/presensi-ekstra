<?php

namespace App\Filament\Resources\Students\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                TextInput::make('username'),
                TextInput::make('nis'),
                TextInput::make('password'),
                Select::make('school_class_id')
                    ->label('Kelas')    
                    ->relationship('schoolClass', 'name')
                    ->loadingMessage('Loading authors...'),
            ]);
    }   
}
