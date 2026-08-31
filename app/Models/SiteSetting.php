<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['nama_komunitas', 'tagline', 'tentang', 'logo', 'telepon', 'email'];

    public static function current(): self
    {
        return static::query()->first() ?? new static([
            'nama_komunitas' => config('app.name', 'SITOMPUL'),
        ]);
    }
}
