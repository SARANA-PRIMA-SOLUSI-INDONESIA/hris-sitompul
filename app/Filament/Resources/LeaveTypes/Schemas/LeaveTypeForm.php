<?php

namespace App\Filament\Resources\LeaveTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                TextInput::make('nama')
                    ->label('Nama Jenis Cuti')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kuota_tahunan')
                    ->label('Kuota Tahunan (hari)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(12),
                Toggle::make('dibayar')
                    ->label('Dibayar')
                    ->default(true),
                TextInput::make('maks_pengajuan')
                    ->label('Maksimum Pengajuan (hari)')
                    ->numeric()
                    ->minValue(1),
                Toggle::make('aktif')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
