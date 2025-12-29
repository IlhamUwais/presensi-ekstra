<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('schedule_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('date')
                    ->required(),
                Select::make('status')
                    ->options([
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'setengah' => 'Setengah',
            'alpha' => 'Alpha',
        ])
                    ->required(),
                TimePicker::make('clock_in'),
                TimePicker::make('clock_out'),
                Textarea::make('reason')
                    ->columnSpanFull(),
                TextInput::make('photo_in'),
                TextInput::make('photo_out'),
                TextInput::make('lat_in')
                    ->numeric(),
                TextInput::make('long_in')
                    ->numeric(),
                TextInput::make('lat_out')
                    ->numeric(),
                TextInput::make('long_out')
                    ->numeric(),
            ]);
    }
}
