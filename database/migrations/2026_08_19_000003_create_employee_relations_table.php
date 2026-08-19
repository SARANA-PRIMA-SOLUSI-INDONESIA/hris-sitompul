<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('jenjang', 20);
            $table->string('institusi');
            $table->string('jurusan')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('hubungan', 20);
            $table->date('tanggal_lahir')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('tipe', 50);
            $table->string('nama_file');
            $table->string('path');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_position_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->date('mulai');
            $table->date('selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_position_histories');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_families');
        Schema::dropIfExists('employee_educations');
    }
};
