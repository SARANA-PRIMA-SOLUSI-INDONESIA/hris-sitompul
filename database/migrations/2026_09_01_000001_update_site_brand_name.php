<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('site_settings')
            ->whereIn('nama_komunitas', ['SITOMBUNG', 'Sitombung', 'sitombung'])
            ->update(['nama_komunitas' => 'SITOMPUL']);
    }

    public function down(): void
    {
        // Data correction is intentionally not reversed to avoid overwriting
        // a name that may have been edited after this migration ran.
    }
};
