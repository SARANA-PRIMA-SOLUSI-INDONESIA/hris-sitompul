<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
            TextInput::make('panggoaran')->label('Panggoaran')->maxLength(255),
            TextInput::make('tempat_lahir')->label('Tempat Tanggal Lahir')->maxLength(255),
            DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
            Select::make('jenis_kelamin')->label('Jenis Kelamin')->options([
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
            ]),
            Textarea::make('alamat_tinggal_saat_ini')
                ->label('Alamat Tinggal saat ini')
                ->rows(3)
                ->columnSpanFull(),
            Textarea::make('alamat_ktp')->label('Alamat KTP')->rows(3)->columnSpanFull(),
            TextInput::make('agama')->label('Agama')->maxLength(100),
            TextInput::make('status_pernikahan')->label('Status Perkawinan')->maxLength(50),
            TextInput::make('pekerjaan')->label('Pekerjaan')->maxLength(255),
            Select::make('status_anggota')->label('Status')->options([
                'anak' => 'ANAK',
                'boru' => 'BORU',
                'bere' => 'BERE',
                'ibebere' => 'IBEBERE',
            ]),
            TextInput::make('no_telp')->label('No. HP')->tel()->maxLength(20),
            TextInput::make('email_pribadi')->label('Email')->email()->maxLength(255),
            Select::make('gol_darah')->label('Gol Darah')->options([
                'A' => 'A',
                'B' => 'B',
                'AB' => 'AB',
                'O' => 'O',
            ]),
            DatePicker::make('tanggal_terdaftar_anggota')->label('Tanggal Terdaftar sebagai Anggota'),
            TextInput::make('no_pegawai')->label('No Anggota')->maxLength(30)->unique(ignoreRecord: true),
            TextInput::make('nik')->label('NIK')->required()->maxLength(30)->unique(ignoreRecord: true),
            FileUpload::make('foto')->label('Foto (Opsional)')->image()->directory('employee-photos')->avatar()->imageEditor(),
            Toggle::make('tampilkan_kartu')->label('Aktifkan QR verifikasi')->default(true)->helperText('QR menampilkan data anggota yang relevan.'),
            CheckboxList::make('visibilitas_field')
                ->label('Kolom yang ditampilkan di QR Card')
                ->options([
                    'nama_lengkap' => 'Nama',
                    'panggoaran' => 'Panggoaran',
                    'tempat_lahir' => 'Tempat Tanggal Lahir',
                    'tanggal_lahir' => 'Tanggal Lahir',
                    'jenis_kelamin' => 'Jenis Kelamin',
                    'alamat_tinggal_saat_ini' => 'Alamat Tinggal saat ini',
                    'alamat_ktp' => 'Alamat KTP',
                    'agama' => 'Agama',
                    'status_pernikahan' => 'Status Perkawinan',
                    'pekerjaan' => 'Pekerjaan',
                    'status_anggota' => 'Status',
                    'no_telp' => 'No. HP',
                    'email_pribadi' => 'Email',
                    'gol_darah' => 'Gol Darah',
                    'tanggal_terdaftar_anggota' => 'Tanggal Terdaftar sebagai Anggota',
                    'no_pegawai' => 'No Anggota',
                    'nik' => 'NIK',
                ])
                ->default(self::defaultVisibility())
                ->formatStateUsing(fn (?array $state): array => array_is_list($state ?? [])
                    ? ($state ?? [])
                    : array_keys(array_filter($state ?? [])))
                ->columns(2)
                ->gridDirection('row')
                ->helperText('Centang kolom yang boleh dilihat ketika QR Card dipindai.')
                ->columnSpanFull(),
        ];
    }

    protected static function defaultVisibility(): array
    {
        return [
            'nama_lengkap', 'panggoaran', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'alamat_tinggal_saat_ini', 'alamat_ktp', 'agama', 'status_pernikahan', 'pekerjaan',
            'status_anggota', 'no_telp', 'email_pribadi', 'gol_darah',
            'tanggal_terdaftar_anggota', 'no_pegawai', 'nik',
        ];
    }
}
