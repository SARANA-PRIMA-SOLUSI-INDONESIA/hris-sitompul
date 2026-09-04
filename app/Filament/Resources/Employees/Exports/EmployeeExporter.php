<?php

namespace App\Filament\Resources\Employees\Exports;

use App\Models\Employee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class EmployeeExporter extends Exporter
{
    protected static ?string $model = Employee::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nama_lengkap')->label('Nama'),
            ExportColumn::make('panggoaran')->label('Panggoaran'),
            ExportColumn::make('tempat_lahir')->label('Tempat Lahir'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            ExportColumn::make('alamat_tinggal_saat_ini')->label('Alamat Tinggal saat ini'),
            ExportColumn::make('alamat_ktp')->label('Alamat KTP'),
            ExportColumn::make('agama')->label('Agama'),
            ExportColumn::make('status_pernikahan')->label('Status Perkawinan'),
            ExportColumn::make('pekerjaan')->label('Pekerjaan'),
            ExportColumn::make('status_anggota')->label('Status'),
            ExportColumn::make('no_telp')->label('No. HP'),
            ExportColumn::make('email_pribadi')->label('Email'),
            ExportColumn::make('gol_darah')->label('Gol Darah'),
            ExportColumn::make('tanggal_terdaftar_anggota')->label('Tanggal Terdaftar sebagai Anggota'),
            ExportColumn::make('no_pegawai')->label('No Anggota'),
            ExportColumn::make('nik')->label('NIK'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export anggota selesai. '.Number::format($export->successful_rows).' '
            .str('baris')->plural($export->successful_rows).' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '
                .str('baris')->plural($failedRowsCount).' gagal diekspor.';
        }

        return $body;
    }

    /**
     * Run exports inline so the download toast appears immediately.
     * (Background queue + hidden topbar meant users never saw the download link.)
     */
    public function getJobConnection(): ?string
    {
        return 'sync';
    }
}
