<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Position;
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

        $departments = Department::factory()->count(6)->create();
        $positions = Position::factory()->count(12)->create();

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@sitombung.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        $hr = User::factory()->create([
            'name' => 'HR Admin',
            'email' => 'hr@sitombung.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $hr->assignRole('hr_admin');

        $employees = collect();
        foreach ($departments->take(3) as $department) {
            $managerUser = User::factory()->create([
                'name' => 'Manager '.$department->nama,
                'email' => 'manager.'.strtolower(str_replace(' ', '.', $department->nama)).'@sitombung.test',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
            $managerUser->assignRole('manager');

            $manager = Employee::factory()->create([
                'department_id' => $department->id,
                'position_id' => $positions->firstWhere('department_id', $department->id)?->id ?? $positions->first()->id,
                'nama_lengkap' => $managerUser->name,
                'user_id' => $managerUser->id,
                'status_kepegawaian' => 'tetap',
            ]);
            $manager->atasan()->associate(null)->save();

            $employees->push($manager);
            $department->kepala_id = $manager->id;
            $department->save();
        }

        $employeeUser = User::factory()->create([
            'name' => 'Karyawan Demo',
            'email' => 'karyawan@sitombung.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $employeeUser->assignRole('karyawan');

        $staff = Employee::factory()->count(25)->create([
            'atasan_id' => fn () => $employees->random()->id,
        ]);
        $staff->first()->update(['user_id' => $employeeUser->id, 'nama_lengkap' => $employeeUser->name]);

        $staff->each(function (Employee $employee) use ($departments, $positions) {
            if ($employee->department_id === null) {
                $employee->department_id = $departments->random()->id;
            }
            if ($employee->position_id === null) {
                $employee->position_id = $positions->random()->id;
            }
            $employee->save();
        });

        $leaveTypes = collect();
        foreach ([
            ['kode' => 'CTH', 'nama' => 'Cuti Tahunan', 'kuota_tahunan' => 12, 'dibayar' => true, 'maks_pengajuan' => 12],
            ['kode' => 'CSK', 'nama' => 'Cuti Sakit', 'kuota_tahunan' => 14, 'dibayar' => true, 'maks_pengajuan' => 14],
            ['kode' => 'CTB', 'nama' => 'Cuti Besar', 'kuota_tahunan' => 30, 'dibayar' => true, 'maks_pengajuan' => 30],
            ['kode' => 'CPR', 'nama' => 'Cuti Pernikahan', 'kuota_tahunan' => 3, 'dibayar' => true, 'maks_pengajuan' => 3],
            ['kode' => 'I', 'nama' => 'Izin', 'kuota_tahunan' => 5, 'dibayar' => false, 'maks_pengajuan' => 5],
        ] as $type) {
            $leaveTypes->push(LeaveType::create($type));
        }

        $staff->each(function (Employee $employee) use ($leaveTypes) {
            if (fake()->boolean(70)) {
                Leave::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveTypes->random()->id,
                    'status' => fake()->randomElement([Leave::STATUS_MENUNGGU, Leave::STATUS_DISETUJUI, Leave::STATUS_DITOLAK]),
                ]);
            }
        });

        foreach ($staff as $employee) {
            $start = now()->subDays(30);
            for ($i = 0; $i < 30; $i++) {
                Attendance::factory()->create([
                    'employee_id' => $employee->id,
                    'tanggal' => $start->copy()->addDays($i)->format('Y-m-d'),
                    'status' => $start->copy()->addDays($i)->isWeekend() ? Attendance::STATUS_HADIR : fake()->randomElement([
                        Attendance::STATUS_HADIR,
                        Attendance::STATUS_HADIR,
                        Attendance::STATUS_HADIR,
                        Attendance::STATUS_IZIN,
                        Attendance::STATUS_SAKIT,
                        Attendance::STATUS_CUTI,
                        Attendance::STATUS_ALPHA,
                    ]),
                    'jam_masuk' => fake()->randomElement(['07:55', '08:00', '08:05', '08:12', '08:30']),
                    'jam_keluar' => '17:00',
                ]);
            }
        }

        foreach ([
            ['kode' => 'GAPOK', 'nama' => 'Gaji Pokok', 'tipe' => 'tunjangan', 'jumlah' => 5000000],
            ['kode' => 'TJM', 'nama' => 'Tunjangan Jabatan', 'tipe' => 'tunjangan', 'jumlah' => 1000000],
            ['kode' => 'TMAK', 'nama' => 'Tunjangan Makan', 'tipe' => 'tunjangan', 'jumlah' => 500000],
            ['kode' => 'TTR', 'nama' => 'Tunjangan Transport', 'tipe' => 'tunjangan', 'jumlah' => 300000],
            ['kode' => 'PBP', 'nama' => 'Potongan BPJS', 'tipe' => 'potongan', 'jumlah' => 250000],
            ['kode' => 'PALP', 'nama' => 'Potongan Alpha', 'tipe' => 'potongan', 'jumlah' => 100000],
        ] as $component) {
            SalaryComponent::create($component);
        }

        $this->command?->info('Database seeded successfully.');
        $this->command?->warn('Login: admin@sitombung.test / password');
    }
}
