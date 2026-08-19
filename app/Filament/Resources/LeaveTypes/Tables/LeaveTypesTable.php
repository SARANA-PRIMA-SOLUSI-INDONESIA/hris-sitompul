<?php

namespace App\Filament\Resources\LeaveTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Jenis Cuti')
                    ->searchable(),
                TextColumn::make('kuota_tahunan')
                    ->label('Kuota Tahunan')
                    ->suffix(' hari'),
                IconColumn::make('dibayar')
                    ->label('Dibayar')
                    ->boolean(),
                TextColumn::make('maks_pengajuan')
                    ->label('Maks Pengajuan')
                    ->suffix(' hari')
                    ->placeholder('-'),
                IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
