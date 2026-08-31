<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)->schema(self::fields()),
            ]);
    }

    protected static function fields(): array
    {
        return [
            TextInput::make('nama_lengkap')->label('Nama')->required()->maxLength(255),
            TextInput::make('nik')->label('NIK')->required()->maxLength(30)->unique(ignoreRecord: true),
            TextInput::make('nama_perusahaan')->label('Nama Perusahaan')->required()->maxLength(255),
            TextInput::make('jabatan')->label('Jabatan')->required()->maxLength(255),
            TextInput::make('no_telp')->label('No. HP')->tel()->required()->maxLength(20),
            TextInput::make('email_pribadi')->label('Email (Opsional)')->email()->maxLength(255),
            Textarea::make('alamat')
                ->label('Alamat (Opsional)')
                ->rows(3)
                ->columnSpanFull(),
            FileUpload::make('foto')->label('Foto (Opsional)')->image()->directory('employee-photos')->avatar()->imageEditor(),
            Toggle::make('tampilkan_kartu')->label('Aktifkan QR verifikasi')->default(true)->helperText('QR hanya menampilkan nama, perusahaan, jabatan, foto, dan status.'),
        ];
    }
}
