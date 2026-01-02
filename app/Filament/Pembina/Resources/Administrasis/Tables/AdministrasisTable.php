<?php

namespace App\Filament\Pembina\Resources\Administrasis\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Ekstrakulikuler;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AdministrasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query) {
                $pembinaId = Auth::id();
                
                // Cari ekskul milik pembina
                $ekstra = Ekstrakulikuler::where('pembina_id', $pembinaId)->first();

                // Jika pembina tidak punya ekskul, jangan tampilkan apa-apa
                if (!$ekstra) {
                    return $query->whereRaw('1 = 0');
                }

                // Filter langsung ke tabel member_ekstras
                return $query
                    ->where('ekstrakulikuler_id', $ekstra->id) // Sesuai nama kolom di migration
                    ->where('status', 'pending');              // Sesuai enum di migration
            })
            ->columns([
                TextColumn::make('user.name') // Relasi ke User, ambil kolom name
                    ->label('Nama Siswa')
                    ->searchable(),
                
                TextColumn::make('user.nis')
                    ->label('NIS')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
               Action::make('approve')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // $record di sini adalah baris 'MemberEkstra'
                        // Jadi tinggal update statusnya aja
                        $record->update([
                            'status' => 'approved',
                            'is_active' => true
                        ]);
                    }),

                // TOMBOL TOLAK (REJECT)
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Update jadi rejected atau hapus (sesuai selera)
                        $record->update(['status' => 'rejected']);
                        // atau $record->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
