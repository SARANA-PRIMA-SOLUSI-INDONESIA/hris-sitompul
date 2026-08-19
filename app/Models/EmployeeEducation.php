<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'jenjang', 'institusi', 'jurusan', 'tahun_lulus', 'ipk',
    ];

    protected function casts(): array
    {
        return [
            'tahun_lulus' => 'integer',
            'ipk' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
