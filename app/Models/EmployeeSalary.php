<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'salary_component_id', 'jumlah', 'berlaku_dari', 'berlaku_sampai',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'berlaku_dari' => 'date',
            'berlaku_sampai' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
