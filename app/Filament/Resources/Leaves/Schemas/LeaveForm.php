<?php

namespace App\Filament\Resources\Leaves\Schemas;

use App\Actions\LeaveCalculator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveForm
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
                        ->required()
                        ->disabled(fn (string $operation): bool => $operation === 'edit')
                        ->dehydrated(),
                    Select::make('leave_type_id')
                        ->label('Jenis Cuti')
                        ->relationship('leaveType', 'nama')
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Select $component, ?string $state, callable $get): void {
                            $employeeId = $get('employee_id');
                            if ($state && $employeeId) {
                                $component->getContainer()
                                    ->getComponent('sisa_cuti')
                                    ->state(LeaveCalculator::remainingQuota((int) $employeeId, (int) $state));
                            }
                        }),
                    TextInput::make('sisa_cuti')
                        ->label('Sisa Cuti')
                        ->placeholder('-')
                        ->disabled()
                        ->dehydrated(false),
                ]),
                Grid::make(3)->schema([
                    DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->live(),
                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (callable $get, callable $set): void {
                            $mulai = $get('tanggal_mulai');
                            $selesai = $get('tanggal_selesai');
                            if ($mulai && $selesai) {
                                $set('jumlah_hari', LeaveCalculator::countWorkingDays($mulai, $selesai));
                            }
                        }),
                    TextInput::make('jumlah_hari')
                        ->label('Jumlah Hari (kerja)')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                ]),
                Textarea::make('alasan')
                    ->label('Alasan')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('lampiran')
                    ->label('Lampiran')
                    ->directory('leave-attachments')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->default('menunggu')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->disabled(),
                Textarea::make('alasan_penolakan')
                    ->label('Alasan Penolakan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
