<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    public const STATUS_HADIR = 'hadir';

    public const STATUS_IZIN = 'izin';

    public const STATUS_SAKIT = 'sakit';

    public const STATUS_CUTI = 'cuti';

    public const STATUS_ALPHA = 'alpha';

    protected $fillable = [
        'employee_id', 'tanggal', 'status', 'jam_masuk', 'jam_keluar', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jam_masuk' => 'datetime:H:i',
            'jam_keluar' => 'datetime:H:i',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
