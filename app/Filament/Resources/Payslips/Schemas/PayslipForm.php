<?php

namespace App\Filament\Resources\Payslips\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PayslipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)->schema([
                    Select::make('employee_id')
                        ->label('Karyawan')
                        ->relationship('employee', 'nama_lengkap')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('periode')
                        ->label('Periode (YYYY-MM)')
                        ->placeholder('2026-08')
                        ->required()
                        ->maxLength(7),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'final' => 'Final',
                        ])
                        ->default('draft')
                        ->required(),
                ]),
                TextInput::make('total')
                    ->label('Total Gaji (Rp)')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->columnSpanFull(),
            ]);
    }
}
