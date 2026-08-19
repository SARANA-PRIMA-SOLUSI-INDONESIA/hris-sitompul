<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('tipe', ['tunjangan', 'potongan'])->default('tunjangan');
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salary_component_id')->constrained()->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->date('berlaku_dari');
            $table->date('berlaku_sampai')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'salary_component_id', 'berlaku_dari'], 'emp_sal_uniq');
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('periode', 7);
            $table->decimal('total', 12, 2)->default(0);
            $table->json('detail')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();

            $table->unique(['employee_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('employee_salaries');
        Schema::dropIfExists('salary_components');
    }
};
