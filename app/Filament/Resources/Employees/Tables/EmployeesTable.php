<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Filament\Resources\Employees\Actions\ConfigureQrCardAction;
use App\Filament\Resources\Employees\Actions\ShowQrCardAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn (): string => 'https://ui-avatars.com/api/?name='.urlencode('Anggota')),
                TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('panggoaran')
                    ->label('Panggoaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_anggota')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state): string => $state ? strtoupper($state) : '-')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('no_telp')
                    ->label('No. HP')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('email_pribadi')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('alamat_tinggal_saat_ini')
                    ->label('Alamat Tinggal saat ini')
                    ->searchable()
                    ->limit(35)
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('Terhapus'),
            ])
            ->defaultSort('nama_lengkap')
            ->recordActions([
                EditAction::make(),
                ConfigureQrCardAction::make(),
                ShowQrCardAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
