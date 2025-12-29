<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get; // <--- WAJIB IMPORT INI

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state)),

            
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'pembina' => 'Pembina', 
                        'siswa' => 'Siswa',
                    ])
                    ->default('siswa')
                    ->required()
                    ->live(), 

         
                TextInput::make('nis')
                    ->label('NIS')
                    ->numeric()
                    ->visible(fn (Get $get) => $get('role') === 'siswa'), 


                    Select::make('school_class_id')
                        ->label('Kelas')    
                        ->relationship('schoolClass', 'name')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->jenjang_kelas} - {$record->name}")
                        ->searchable(['name', 'jenjang_kelas'])
                        ->preload()
                        ->visible(fn (Get $get) => $get('role') === 'siswa'),


                        TextInput::make('nip')
                            ->label('NIP')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('role') === 'pembina'),
            ]);
    }
}