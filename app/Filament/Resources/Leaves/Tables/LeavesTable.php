<?php

namespace App\Filament\Resources\Leaves\Tables;

use App\Actions\ApproveLeave;
use App\Models\Leave;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeavesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama_lengkap')
                    ->label('Karyawan')
                    ->searchable(),
                TextColumn::make('leaveType.nama')
                    ->label('Jenis Cuti')
                    ->badge()
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                TextColumn::make('jumlah_hari')
                    ->label('Hari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Leave::STATUS_DISETUJUI => 'success',
                        Leave::STATUS_MENUNGGU => 'warning',
                        Leave::STATUS_DITOLAK => 'danger',
                        Leave::STATUS_DIBATALKAN => 'gray',
                        Leave::STATUS_DRAFT => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('approved_at')
                    ->label('Diproses Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
                SelectFilter::make('leave_type_id')
                    ->label('Jenis Cuti')
                    ->relationship('leaveType', 'nama'),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
            ->recordActions([
                EditAction::make(),
                TableAction::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Leave $record): bool => $record->status === Leave::STATUS_MENUNGGU)
                    ->authorize(fn (Leave $record): bool => auth()->user()?->can('approve', $record) ?? false)
                    ->requiresConfirmation()
                    ->action(function (Leave $record): void {
                        try {
                            ApproveLeave::run($record, auth()->user());
                            Notification::make()
                                ->title('Cuti disetujui')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Gagal menyetujui cuti')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                TableAction::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Leave $record): bool => $record->status === Leave::STATUS_MENUNGGU)
                    ->authorize(fn (Leave $record): bool => auth()->user()?->can('approve', $record) ?? false)
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Leave $record, array $data): void {
                        try {
                            ApproveLeave::reject($record, auth()->user(), $data['alasan_penolakan']);
                            Notification::make()
                                ->title('Cuti ditolak')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Gagal menolak cuti')
                                ->body($e->getMessage())
                                ->danger()
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
