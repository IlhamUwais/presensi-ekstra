<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden; // <--- 1. WAJIB IMPORT INI

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nip')
                    ->numeric(), 

                TextInput::make('password')
                    ->password() 
                    ->required(fn ($operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state)),


                Hidden::make('role')
                    ->default('pembina'), 
            ]);
    }
}