<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'slug', 'ringkasan', 'isi', 'gambar', 'diterbitkan', 'diterbitkan_pada', 'user_id'];

    protected function casts(): array
    {
        return ['diterbitkan' => 'boolean', 'diterbitkan_pada' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
