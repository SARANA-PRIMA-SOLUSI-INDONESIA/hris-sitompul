<?php

namespace App\Filament\Resources\SalaryComponents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SalaryComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)->schema([
                    TextInput::make('kode')
                        ->label('Kode')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true),
                    TextInput::make('nama')
                        ->label('Nama Komponen')
                        ->required()
                        ->maxLength(255),
                    Select::make('tipe')
                        ->label('Tipe')
                        ->options([
                            'tunjangan' => 'Tunjangan',
                            'potongan' => 'Potongan',
                        ])
                        ->default('tunjangan')
                        ->required(),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('jumlah')
                        ->label('Jumlah (Rp)')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->prefix('Rp'),
                    Toggle::make('aktif')
                        ->label('Aktif')
                        ->default(true),
                ]),
            ]);
    }
}
