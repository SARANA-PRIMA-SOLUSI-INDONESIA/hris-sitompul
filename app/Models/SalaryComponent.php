<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', 'nama', 'tipe', 'jumlah', 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function employeeSalaries(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
