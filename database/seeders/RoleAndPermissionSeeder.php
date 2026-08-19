<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect([
            'view_dashboard',
            'view_any_employee', 'view_employee', 'create_employee', 'update_employee', 'delete_employee', 'restore_employee',
            'view_any_department', 'view_department', 'create_department', 'update_department', 'delete_department', 'restore_department',
            'view_any_position', 'view_position', 'create_position', 'update_position', 'delete_position', 'restore_position',
            'view_any_leave', 'view_leave', 'create_leave', 'update_leave', 'delete_leave',
            'approve_leave', 'reject_leave',
            'view_any_leave_type', 'view_leave_type', 'create_leave_type', 'update_leave_type', 'delete_leave_type',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
            'import_attendance',
            'view_any_salary_component', 'view_salary_component', 'create_salary_component', 'update_salary_component', 'delete_salary_component',
            'view_any_employee_salary', 'view_employee_salary', 'create_employee_salary', 'update_employee_salary', 'delete_employee_salary',
            'view_any_payslip', 'view_payslip', 'create_payslip', 'update_payslip', 'delete_payslip',
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_any_activity_log',
        ])->map(fn (string $permission) => Permission::firstOrCreate(['name' => $permission]));

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $hrAdmin = Role::firstOrCreate(['name' => 'hr_admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $karyawan = Role::firstOrCreate(['name' => 'karyawan']);

        $superAdmin->syncPermissions($permissions->pluck('name'));

        $hrAdmin->syncPermissions($permissions->reject(fn (Permission $p) => in_array($p->name, [
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_any_activity_log',
        ]))->pluck('name'));

        $manager->syncPermissions([
            'view_dashboard',
            'view_employee', 'view_leave', 'approve_leave', 'reject_leave',
            'view_attendance', 'view_payslip', 'view_employee_salary',
        ]);

        $karyawan->syncPermissions([
            'view_dashboard',
            'view_employee',
            'view_leave', 'create_leave', 'update_leave', 'delete_leave',
            'view_attendance', 'view_payslip',
        ]);
    }
}
