<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Component; // Pastikan namespace Component ini benar (v4)

class CustomLogin extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getLoginFormComponent(), 
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username') // Label ganti jadi Username saja
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        // Langsung return username dan password.
        // Tidak perlu cek apakah ini email atau bukan.
        return [
            'username' => $data['username'],
            'password'  => $data['password'],
        ];
    }
}