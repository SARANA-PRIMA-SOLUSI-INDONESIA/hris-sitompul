<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->json('visibilitas_field')->nullable()->after('tampilkan_kartu');
            $table->string('panggoaran')->nullable()->after('nama_lengkap');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->text('alamat_tinggal_saat_ini')->nullable()->after('jenis_kelamin');
            $table->text('alamat_ktp')->nullable()->after('alamat_tinggal_saat_ini');
            $table->string('agama')->nullable()->after('alamat_ktp');
            $table->string('status_pernikahan')->nullable()->after('agama');
            $table->string('pekerjaan')->nullable()->after('status_pernikahan');
            $table->enum('status_anggota', ['anak', 'boru', 'bere', 'ibebere'])->nullable()->after('pekerjaan');
            $table->string('gol_darah', 2)->nullable()->after('email_pribadi');
            $table->date('tanggal_terdaftar_anggota')->nullable()->after('gol_darah');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->dropColumn([
                'panggoaran', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'alamat_tinggal_saat_ini', 'alamat_ktp', 'agama', 'status_pernikahan',
                'pekerjaan', 'status_anggota', 'gol_darah', 'tanggal_terdaftar_anggota',
            ]);
        });
    }
};
