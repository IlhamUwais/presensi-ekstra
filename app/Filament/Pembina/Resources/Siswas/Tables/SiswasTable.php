<?php

namespace App\Filament\Pembina\Resources\Siswas\Tables;

use Filament\Tables\Table;
use App\Models\MemberEkstra;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Ekstrakulikuler; // ⚠️ Pastikan kamu punya Model ini
use Illuminate\Database\Query\Builder as QueryBuilder; // Untuk Subquery

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 👇 LOGIC FILTER BARU (RELASI 3 TABEL)
            ->modifyQueryUsing(function (Builder $query) {
                // 1. Ambil ID Pembina yang sedang login
                $pembinaId = Auth::id();

                // 2. Cari ID Ekstra yang dipegang pembina ini
                // (Asumsi nama Model kamu Ekstrakulikuler)
                $ekstra = Ekstrakulikuler::where('pembina_id', $pembinaId)->first();

                // Jaga-jaga kalau pembina belum diassign ke ekstra manapun
                if (!$ekstra) {
                    return $query->whereRaw('1 = 0'); // Jangan tampilkan siapapun
                }

                // 3. Filter Siswa:
                // "Tampilkan User yang ID-nya ada di dalam tabel member_ekstras
                //  khusus untuk ekstra yang dipegang pembina ini"
                return $query->whereIn('id', function (QueryBuilder $subQuery) use ($ekstra) {
                    $subQuery->select('user_id')
                             ->from('member_ekstras')
                             ->where('ekstrakulikuler_id', $ekstra->id) 
                             ->where('status', 'approved');
                             // ⚠️ Cek database: namanya 'ekstrakulikuler_id' atau 'ekstra_id'?
                });
            })
            // 👆 SELESAI LOGIC

            ->columns([
                TextColumn::make('nis')->sortable(),
                TextColumn::make('name')->label('Nama Siswa')->searchable(),
                TextColumn::make('schoolClass.name')->label('Kelas'),

                // Tampilkan nama ekstra biar jelas (Opsional, butuh relasi)
            ])  
            ->filters([
                SelectFilter::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->label('Kelas'),
                //
            ])
            ->recordActions([
                Action::make('Kick')
                ->action(function ($record) {
    // 1. Ambil ID Pembina
    $pembinaId = Auth::id();
    
    // 2. Cari ID Ekstra milik Pembina
    $ekstra = Ekstrakulikuler::where('pembina_id', $pembinaId)->first();
    
    // 3. Cari data MemberEkstra yang menghubungkan Siswa ini ($record->id) dengan Ekstra tersebut
    $member = MemberEkstra::where('user_id', $record->id)
                ->where('ekstrakulikuler_id', $ekstra->id)
                ->first();

    // 4. Kalau ketemu, baru di-kick (Hapus atau Ubah Status)
    if ($member) {
        // Opsi A: Hapus Permanen (Siswa hilang dari daftar)
        $member->delete(); 
        
        // Opsi B: Ubah status jadi rejected (Siswa masih ada di database tapi status rejected)
        // $member->update(['status' => 'rejected', 'is_active' => false]);
        
        // Kirim notifikasi sukses
        \Filament\Notifications\Notification::make()
            ->title('Siswa berhasil dikeluarkan')
            ->success()
            ->send();
    }
}),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}