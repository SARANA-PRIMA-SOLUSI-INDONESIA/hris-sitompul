<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DepartmentForm
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
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255),
                Select::make('parent_id')
                    ->label('Departemen Induk')
                    ->relationship('parent', 'nama'),
                Select::make('kepala_id')
                    ->label('Kepala Departemen')
                    ->relationship('kepala', 'nama_lengkap'),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Toggle::make('aktif')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
