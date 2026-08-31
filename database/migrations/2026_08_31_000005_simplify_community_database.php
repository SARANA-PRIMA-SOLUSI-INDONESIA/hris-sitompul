<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('nama_perusahaan');
            }
        });

        Schema::disableForeignKeyConstraints();

        foreach ([
            'employee_position_histories', 'employee_documents', 'employee_families',
            'employee_educations', 'employee_salaries', 'payslips', 'salary_components',
            'leaves', 'leave_types', 'attendances', 'positions', 'departments',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::table('employees', function (Blueprint $table) {
            foreach (['user_id', 'department_id', 'position_id', 'atasan_id'] as $column) {
                $table->dropForeign([$column]);
            }

            foreach ([
                'user_id', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama',
                'status_pernikahan', 'status_kepegawaian', 'tanggal_bergabung',
                'tanggal_kontrak_selesai', 'department_id', 'position_id', 'atasan_id',
                'tanggal_keluar', 'alasan_keluar',
            ] as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // The removed HR modules are intentionally not restored.
    }
};
