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

        $setting = static::query()->first() ?? new static([
            'nama_komunitas' => config('app.name', 'Marga Sitompul'),
        ]);

        if (strtolower((string) $setting->nama_komunitas) === 'sitombung') {
            $setting->nama_komunitas = 'SITOMPUL';
        }

        return $setting;
    }
}
