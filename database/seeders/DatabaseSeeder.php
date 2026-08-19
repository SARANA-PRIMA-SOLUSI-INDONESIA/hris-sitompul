<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        User::firstOrCreate(
            ['email' => 'admin@sitombung.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        )->assignRole('super_admin');

        User::firstOrCreate(
            ['email' => 'hr@sitombung.test'],
            [
                'name' => 'HR Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        )->assignRole('hr_admin');

        foreach ([
            ['kode' => 'CTH', 'nama' => 'Cuti Tahunan', 'kuota_tahunan' => 12, 'dibayar' => true, 'maks_pengajuan' => 12],
            ['kode' => 'CSK', 'nama' => 'Cuti Sakit', 'kuota_tahunan' => 14, 'dibayar' => true, 'maks_pengajuan' => 14],
            ['kode' => 'CTB', 'nama' => 'Cuti Besar', 'kuota_tahunan' => 30, 'dibayar' => true, 'maks_pengajuan' => 30],
            ['kode' => 'CPR', 'nama' => 'Cuti Pernikahan', 'kuota_tahunan' => 3, 'dibayar' => true, 'maks_pengajuan' => 3],
            ['kode' => 'I', 'nama' => 'Izin', 'kuota_tahunan' => 5, 'dibayar' => false, 'maks_pengajuan' => 5],
        ] as $type) {
            LeaveType::firstOrCreate(['kode' => $type['kode']], $type);
        }

        foreach ([
            ['kode' => 'GAPOK', 'nama' => 'Gaji Pokok', 'tipe' => 'tunjangan', 'jumlah' => 5000000],
            ['kode' => 'TJM', 'nama' => 'Tunjangan Jabatan', 'tipe' => 'tunjangan', 'jumlah' => 1000000],
            ['kode' => 'TMAK', 'nama' => 'Tunjangan Makan', 'tipe' => 'tunjangan', 'jumlah' => 500000],
            ['kode' => 'TTR', 'nama' => 'Tunjangan Transport', 'tipe' => 'tunjangan', 'jumlah' => 300000],
            ['kode' => 'PBP', 'nama' => 'Potongan BPJS', 'tipe' => 'potongan', 'jumlah' => 250000],
            ['kode' => 'PALP', 'nama' => 'Potongan Alpha', 'tipe' => 'potongan', 'jumlah' => 100000],
        ] as $component) {
            SalaryComponent::firstOrCreate(['kode' => $component['kode']], $component);
        }

        $this->command?->info('Database seeded successfully (production).');
        $this->command?->warn('Login: admin@sitombung.test / password');
    }
}
