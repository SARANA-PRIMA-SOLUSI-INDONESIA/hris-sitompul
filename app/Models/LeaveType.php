<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'kode', 'kuota_tahunan', 'dibayar', 'maks_pengajuan', 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'dibayar' => 'boolean',
            'aktif' => 'boolean',
        ];
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
