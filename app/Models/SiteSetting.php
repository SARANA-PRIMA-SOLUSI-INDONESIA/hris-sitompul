<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['nama_komunitas', 'tagline', 'tentang', 'logo', 'telepon', 'email'];

    public static function current(): self
    {
        if (! Schema::hasTable('site_settings')) {
            return new static([
                'nama_komunitas' => config('app.name', 'Marga Sitompul'),
            ]);
        }

        return static::query()->first() ?? new static([
            'nama_komunitas' => config('app.name', 'Marga Sitompul'),
        ]);
    }
}
