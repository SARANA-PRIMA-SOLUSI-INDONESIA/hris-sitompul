<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PositionForm
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
                    ->label('Nama Jabatan')
                    ->required()
                    ->maxLength(255),
                Select::make('level')
                    ->label('Tingkat')
                    ->options([
                        'staff' => 'Staff',
                        'senior_staff' => 'Senior Staff',
                        'supervisor' => 'Supervisor',
                        'manager' => 'Manager',
                        'direktur' => 'Direktur',
                    ]),
                Select::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'nama'),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Toggle::make('aktif')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
