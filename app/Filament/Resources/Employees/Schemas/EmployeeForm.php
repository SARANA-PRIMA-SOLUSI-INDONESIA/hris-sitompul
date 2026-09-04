<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions\Action;
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
            Hidden::make('visibilitas_field')->default(self::defaultVisibility()),
            TextInput::make('nama_lengkap')->label('Nama')->required()->maxLength(255)->suffixAction(self::visibilityAction('nama_lengkap')),
            TextInput::make('panggoaran')->label('Panggoaran')->maxLength(255)->suffixAction(self::visibilityAction('panggoaran')),
            TextInput::make('tempat_lahir')->label('Tempat Tanggal Lahir')->maxLength(255)->suffixAction(self::visibilityAction('tempat_lahir')),
            DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->suffixAction(self::visibilityAction('tanggal_lahir')),
            Select::make('jenis_kelamin')->label('Jenis Kelamin')->options([
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
            ])->suffixAction(self::visibilityAction('jenis_kelamin')),
            Textarea::make('alamat_tinggal_saat_ini')
                ->label('Alamat Tinggal saat ini')
                ->rows(3)
                ->columnSpanFull()
                ->suffixAction(self::visibilityAction('alamat_tinggal_saat_ini')),
            Textarea::make('alamat_ktp')->label('Alamat KTP')->rows(3)->columnSpanFull()->suffixAction(self::visibilityAction('alamat_ktp')),
            TextInput::make('agama')->label('Agama')->maxLength(100)->suffixAction(self::visibilityAction('agama')),
            TextInput::make('status_pernikahan')->label('Status Perkawinan')->maxLength(50)->suffixAction(self::visibilityAction('status_pernikahan')),
            TextInput::make('pekerjaan')->label('Pekerjaan')->maxLength(255)->suffixAction(self::visibilityAction('pekerjaan')),
            Select::make('status_anggota')->label('Status')->options([
                'anak' => 'ANAK',
                'boru' => 'BORU',
                'bere' => 'BERE',
                'ibebere' => 'IBEBERE',
            ])->suffixAction(self::visibilityAction('status_anggota')),
            TextInput::make('no_telp')->label('No. HP')->tel()->maxLength(20)->suffixAction(self::visibilityAction('no_telp')),
            TextInput::make('email_pribadi')->label('Email')->email()->maxLength(255)->suffixAction(self::visibilityAction('email_pribadi')),
            Select::make('gol_darah')->label('Gol Darah')->options([
                'A' => 'A',
                'B' => 'B',
                'AB' => 'AB',
                'O' => 'O',
            ])->suffixAction(self::visibilityAction('gol_darah')),
            DatePicker::make('tanggal_terdaftar_anggota')->label('Tanggal Terdaftar sebagai Anggota')->suffixAction(self::visibilityAction('tanggal_terdaftar_anggota')),
            TextInput::make('no_pegawai')->label('No Anggota')->maxLength(30)->unique(ignoreRecord: true)->suffixAction(self::visibilityAction('no_pegawai')),
            TextInput::make('nik')->label('NIK')->required()->maxLength(30)->unique(ignoreRecord: true)->suffixAction(self::visibilityAction('nik')),
            FileUpload::make('foto')->label('Foto (Opsional)')->image()->directory('employee-photos')->avatar()->imageEditor(),
            Toggle::make('tampilkan_kartu')->label('Aktifkan QR verifikasi')->default(true)->helperText('QR menampilkan data anggota yang relevan.'),
        ];
    }

    protected static function defaultVisibility(): array
    {
        return array_fill_keys([
            'nama_lengkap', 'panggoaran', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'alamat_tinggal_saat_ini', 'alamat_ktp', 'agama', 'status_pernikahan', 'pekerjaan',
            'status_anggota', 'no_telp', 'email_pribadi', 'gol_darah',
            'tanggal_terdaftar_anggota', 'no_pegawai', 'nik',
        ], true);
    }

    protected static function visibilityAction(string $field): Action
    {
        return Action::make('toggle_'.$field)
            ->icon(fn (callable $get): string => ($get('visibilitas_field.'.$field) ?? true)
                ? 'heroicon-o-eye'
                : 'heroicon-o-eye-slash')
            ->color(fn (callable $get): string => ($get('visibilitas_field.'.$field) ?? true) ? 'success' : 'gray')
            ->tooltip(fn (callable $get): string => ($get('visibilitas_field.'.$field) ?? true)
                ? 'Tampilkan di QR'
                : 'Sembunyikan dari QR')
            ->action(function (callable $get, callable $set) use ($field): void {
                $set('visibilitas_field.'.$field, ! ($get('visibilitas_field.'.$field) ?? true));
            });
    }
}
