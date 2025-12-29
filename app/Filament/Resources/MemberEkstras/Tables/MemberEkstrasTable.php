<?php

namespace App\Filament\Resources\MemberEkstras\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Services\EkstraApprovalService;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class MemberEkstrasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstra.name')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('ACC')
                    ->color('success')
                    ->visible(fn ($record) =>
                        $record->status === 'pending'
                        && in_array(auth()->user()->role, ['admin', 'pembina'])
                    )
                    ->action(fn ($record) =>
                        app(EkstraApprovalService::class)->approve($record)
                    )
                    ->requiresConfirmation(),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn ($record) =>
                        $record->status === 'pending'
                        && in_array(auth()->user()->role, ['admin', 'pembina'])
                    )
                    ->action(fn ($record) =>
                        app(EkstraApprovalService::class)->reject($record)
                    )
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
