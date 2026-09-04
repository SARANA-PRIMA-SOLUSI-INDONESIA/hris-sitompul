<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'no_pegawai',
        'slug',
        'tampilkan_kartu',
        'visibilitas_field',
        'nama_lengkap',
        'panggoaran',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_tinggal_saat_ini',
        'alamat_ktp',
        'agama',
        'status_pernikahan',
        'pekerjaan',
        'status_anggota',
        'no_telp',
        'email_pribadi',
        'gol_darah',
        'tanggal_terdaftar_anggota',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'tampilkan_kartu' => 'boolean',
            'visibilitas_field' => 'array',
            'tanggal_lahir' => 'date',
            'tanggal_terdaftar_anggota' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_lengkap', 'panggoaran', 'nik', 'no_telp', 'email_pribadi', 'status_anggota',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function isAktif(): bool
    {
        return true;
    }

    public function fieldIsVisible(string $field): bool
    {
        $visibility = $this->visibilitas_field;

        if (! is_array($visibility) || $visibility === []) {
            return true;
        }

        return array_is_list($visibility)
            ? in_array($field, $visibility, true)
            : ($visibility[$field] ?? true);
    }

    public static function qrFieldLabels(): array
    {
        return [
            'nama_lengkap' => 'Nama', 'panggoaran' => 'Panggoaran', 'tempat_lahir' => 'Tempat Tanggal Lahir',
            'tanggal_lahir' => 'Tanggal Lahir', 'jenis_kelamin' => 'Jenis Kelamin',
            'alamat_tinggal_saat_ini' => 'Alamat Tinggal saat ini', 'alamat_ktp' => 'Alamat KTP',
            'agama' => 'Agama', 'status_pernikahan' => 'Status Perkawinan', 'pekerjaan' => 'Pekerjaan',
            'status_anggota' => 'Status', 'no_telp' => 'No. HP', 'email_pribadi' => 'Email',
            'gol_darah' => 'Gol Darah', 'tanggal_terdaftar_anggota' => 'Tanggal Terdaftar sebagai Anggota',
            'no_pegawai' => 'No Anggota', 'nik' => 'NIK',
        ];
    }

    public function visibleQrFields(): array
    {
        $visibility = $this->visibilitas_field;

        return is_array($visibility) && $visibility !== []
            ? (array_is_list($visibility) ? $visibility : array_keys(array_filter($visibility)))
            : array_keys(static::qrFieldLabels());
    }
}
