<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Actions\GenerateEmployeeNumber;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Data Pribadi')
                            ->icon('heroicon-o-user')
                            ->schema(self::personalFields()),
                        Tabs\Tab::make('Data Kepegawaian')
                            ->icon('heroicon-o-briefcase')
                            ->schema(self::employmentFields()),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function personalFields(): array
    {
        return [
            Grid::make(3)->schema([
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nik')
                    ->label('NIK (KTP)')
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true),
                TextInput::make('email_pribadi')
                    ->label('Email Pribadi')
                    ->email()
                    ->maxLength(255),
            ]),
            Grid::make(3)->schema([
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->maxLength(255),
                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir'),
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                    ->default('L')
                    ->required(),
            ]),
            Grid::make(3)->schema([
                Select::make('agama')
                    ->label('Agama')
                    ->options(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])
                    ->searchable(),
                Select::make('status_pernikahan')
                    ->label('Status Pernikahan')
                    ->options([
                        'lajang' => 'Lajang',
                        'menikah' => 'Menikah',
                        'cerai' => 'Cerai',
                    ]),
                TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(20),
            ]),
            Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3)
                ->columnSpanFull(),
            FileUpload::make('foto')
                ->label('Foto')
                ->image()
                ->directory('employee-photos')
                ->avatar()
                ->imageEditor(),
        ];
    }

    protected static function employmentFields(): array
    {
        return [
            Grid::make(3)->schema([
                TextInput::make('no_pegawai')
                    ->label('No. Pegawai (NIP)')
                    ->default(fn () => GenerateEmployeeNumber::run())
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->dehydrated(),
                Select::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->options([
                        'tetap' => 'Tetap',
                        'kontrak' => 'Kontrak',
                        'magang' => 'Magang',
                    ])
                    ->default('kontrak')
                    ->required(),
                DatePicker::make('tanggal_bergabung')
                    ->label('Tanggal Bergabung')
                    ->required()
                    ->default(now()),
            ]),
            Grid::make(3)->schema([
                Select::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'nama')
                    ->searchable()
                    ->preload(),
                Select::make('position_id')
                    ->label('Jabatan')
                    ->relationship('position', 'nama')
                    ->searchable()
                    ->preload(),
                Select::make('atasan_id')
                    ->label('Atasan Langsung')
                    ->relationship('atasan', 'nama_lengkap')
                    ->searchable()
                    ->preload(),
            ]),
            Grid::make(3)->schema([
                DatePicker::make('tanggal_kontrak_selesai')
                    ->label('Tanggal Selesai Kontrak')
                    ->visible(fn (callable $get): bool => $get('status_kepegawaian') === 'kontrak'),
                DatePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar'),
                TextInput::make('alasan_keluar')
                    ->label('Alasan Keluar')
                    ->maxLength(255),
            ]),
            Grid::make(1)->schema([
                Toggle::make('tampilkan_kartu')
                    ->label('Tampilkan Kartu Nama Publik (QR)')
                    ->default(true)
                    ->helperText('Jika aktif, kartu nama digital karyawan dapat diakses publik via QR code.'),
            ]),
        ];
    }
}
