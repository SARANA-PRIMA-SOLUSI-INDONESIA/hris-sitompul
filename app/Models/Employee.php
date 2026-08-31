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
        'nama_lengkap',
        'nama_perusahaan',
        'jabatan',
        'nik',
        'alamat',
        'no_telp',
        'email_pribadi',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'tampilkan_kartu' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_lengkap', 'nama_perusahaan', 'jabatan', 'nik', 'no_telp', 'email_pribadi',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function isAktif(): bool
    {
        return true;
    }
}
