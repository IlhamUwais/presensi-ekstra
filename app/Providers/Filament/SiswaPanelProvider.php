<?php

namespace App\Providers\Filament;

use App\Filament\Auth\LoginSiswa;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Siswa\Pages\PresensiPage;
use Illuminate\Container\Attributes\Log;

class SiswaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('siswa')
            ->path('siswa')
            ->homeUrl('/siswa')
            ->login(LoginSiswa::class)
            ->colors([
                'primary' => Color::Amber,
            ])

            // 🔑 WAJIB ADA
        ->discoverResources(in: app_path('Filament/Siswa/Resources'), for: 'App\\Filament\\Siswa\\Resources')
        ->discoverPages(in: app_path('Filament/Siswa/Pages'), for: 'App\\Filament\\Siswa\\Pages')

            // (opsional) kalau mau register manual
            // ->pages([
            //     PresensiPage::class,
            // ])

            ->middleware([
                'web',
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
