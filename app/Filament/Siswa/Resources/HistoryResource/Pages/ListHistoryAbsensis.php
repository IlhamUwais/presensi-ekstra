<?php

namespace App\Filament\Siswa\Resources\HistoryAbsensiResource\Pages;

use App\Filament\Siswa\Resources\HistoryAbsensiResource;
use Filament\Resources\Pages\ListRecords;

class ListHistoryAbsensis extends ListRecords
{
    protected static string $resource = HistoryAbsensiResource::class;

    // Kita kosongkan header actions agar tidak ada tombol "New"
}
