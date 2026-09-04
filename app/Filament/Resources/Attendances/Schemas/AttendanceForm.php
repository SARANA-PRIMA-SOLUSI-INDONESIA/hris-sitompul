<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)->schema([
                    Select::make('employee_id')
                        ->label('Anggota')
                        ->relationship('employee', 'nama_lengkap')
                        ->searchable()
                        ->preload()
                        ->required(),
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required()
                        ->default(now()),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'hadir' => 'Hadir',
                            'izin' => 'Izin',
                            'sakit' => 'Sakit',
                            'cuti' => 'Cuti',
                            'alpha' => 'Alpha',
                        ])
                        ->default('hadir')
                        ->required(),
                ]),
                Grid::make(3)->schema([
                    TextInput::make('jam_masuk')
                        ->label('Jam Masuk')
                        ->type('time'),
                    TextInput::make('jam_keluar')
                        ->label('Jam Keluar')
                        ->type('time'),
                    TextInput::make('keterangan')
                        ->label('Keterangan')
                        ->maxLength(255),
                ]),
            ]);
    }
}
