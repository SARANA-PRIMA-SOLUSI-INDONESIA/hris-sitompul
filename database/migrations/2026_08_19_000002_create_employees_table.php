<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('no_pegawai', 30)->unique();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('nama_lengkap');
            $table->string('nik', 30)->unique();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('agama')->nullable();
            $table->string('status_pernikahan', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('email_pribadi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_kepegawaian', ['tetap', 'kontrak', 'magang'])->default('kontrak');
            $table->date('tanggal_bergabung');
            $table->date('tanggal_kontrak_selesai')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('atasan_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
