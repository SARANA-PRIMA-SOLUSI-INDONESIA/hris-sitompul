<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ->defaultImageUrl(fn (): string => 'https://ui-avatars.com/api/?name='.urlencode('Karyawan')),
                TextColumn::make('no_pegawai')
                    ->label('No. Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.nama')
                    ->label('Jabatan')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('department.nama')
                    ->label('Departemen')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('status_kepegawaian')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tetap' => 'success',
                        'kontrak' => 'warning',
                        'magang' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('tanggal_bergabung')
                    ->label('Bergabung')
                    ->date()
                    ->sortable(),
                TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('Terhapus'),
                SelectFilter::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'nama')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('position_id')
                    ->label('Jabatan')
                    ->relationship('position', 'nama')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status_kepegawaian')
                    ->label('Status')
                    ->options([
                        'tetap' => 'Tetap',
                        'kontrak' => 'Kontrak',
                        'magang' => 'Magang',
                    ]),
            ])
            ->defaultSort('no_pegawai')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
