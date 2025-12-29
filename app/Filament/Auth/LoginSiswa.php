<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Component;

class LoginSiswa extends Login
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

    /**
     * 🔑 LOGIN UTAMA SISWA = NIS
     */
    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('nis')
            ->label('NIS')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    /**
     * Credential login
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nis' => $data['nis'],     // ✅ SEKARANG PASTI ADA
            'password' => $data['password'],
            'role' => 'siswa',         // ✅ kunci keamanan
        ];
    }
}
