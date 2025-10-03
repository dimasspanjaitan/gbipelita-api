<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionsMeta;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Seed Permissions Metadata (semua izin yang ada di sistem)
        $permissions = [
            // Master Data
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Pengguna', 'route_name' => 'master.users.view', 'action' => 'view', 'permission_name' => 'master_users_view'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Pengguna', 'route_name' => 'master.users.add', 'action' => 'add', 'permission_name' => 'master_users_add'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Pengguna', 'route_name' => 'master.users.edit', 'action' => 'edit', 'permission_name' => 'master_users_edit'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Pengguna', 'route_name' => 'master.users.delete', 'action' => 'delete', 'permission_name' => 'master_users_delete'],

            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Role', 'route_name' => 'master.roles.view', 'action' => 'view', 'permission_name' => 'master_roles_view'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Role', 'route_name' => 'master.roles.add', 'action' => 'add', 'permission_name' => 'master_roles_add'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Role', 'route_name' => 'master.roles.edit', 'action' => 'edit', 'permission_name' => 'master_roles_edit'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Role', 'route_name' => 'master.roles.delete', 'action' => 'delete', 'permission_name' => 'master_roles_delete'],

            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Department', 'route_name' => 'master.departments.view', 'action' => 'view', 'permission_name' => 'master_departments_view'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Department', 'route_name' => 'master.departments.add', 'action' => 'add', 'permission_name' => 'master_departments_add'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Department', 'route_name' => 'master.departments.edit', 'action' => 'edit', 'permission_name' => 'master_departments_edit'],
            ['module' => 'master', 'parent_menu' => 'Master Data', 'menu' => 'Department', 'route_name' => 'master.departments.delete', 'action' => 'delete', 'permission_name' => 'master_departments_delete'],

            // Penjadwalan
            ['module' => 'schedule', 'parent_menu' => 'Penjadwalan', 'menu' => 'Lihat Jadwal', 'route_name' => 'schedule.view', 'action' => 'view', 'permission_name' => 'schedule_view'],
            ['module' => 'schedule', 'parent_menu' => 'Penjadwalan', 'menu' => 'Isi Ketersediaan', 'route_name' => 'schedule.availability', 'action' => 'add', 'permission_name' => 'schedule_availability_add'],
            ['module' => 'schedule', 'parent_menu' => 'Penjadwalan', 'menu' => 'Tugaskan Jadwal', 'route_name' => 'schedule.assign', 'action' => 'edit', 'permission_name' => 'schedule_assign_manual'],
        ];

        foreach ($permissions as $permission) {
            PermissionsMeta::firstOrCreate(
                ['permission_name' => $permission['permission_name']],
                array_merge($permission, ['id' => Str::uuid()])
            );
        }

        // 2. Sinkronisasi permissions dari meta ke tabel Spatie
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['permission_name']]);
        }

        // 3. Buat Role dan Assign Permissions
        // A. Role Sistem
        $developerRole = Role::firstOrCreate(['name' => 'developer']);
        $developerRole->givePermissionTo(Permission::all());

        // B. Role Hierarki Pelayanan
        $pastorYouthRole = Role::firstOrCreate(['name' => 'pastor_youth']);
        $pastorIcRole = Role::firstOrCreate(['name' => 'pastor_ic']);
        $departmentHeadRole = Role::firstOrCreate(['name' => 'department_head']);
        $divisionLeaderRole = Role::firstOrCreate(['name' => 'division_leader']);

        // C. Role Tingkat Keterlibatan
        $coreTeamRole = Role::firstOrCreate(['name' => 'core_team']);
        $volunteerRole = Role::firstOrCreate(['name' => 'volunteer']);
        $newcomerRole = Role::firstOrCreate(['name' => 'newcomer']);

        // Assign permissions ke role-role dasar
        
        $pastorYouthRole->givePermissionto([
            'master_users_view',
            'schedule_view',
        ]);
        
        $departmentHeadRole->givePermissionTo([
            'master_users_view',
            'master_roles_view',
            'master_roles_add',
            'master_roles_edit',
            'schedule_view',
            'schedule_assign_manual',
        ]);

        $divisionLeaderRole->givePermissionTo([
            'master_users_view',
            'schedule_view',
            'schedule_assign_manual',
        ]);
        
        $coreTeamRole->givePermissionTo([
            'schedule_view',
            'schedule_availability_add',
        ]);
        
        $volunteerRole->givePermissionTo([
            'schedule_view',
            'schedule_availability_add',
        ]);
    }
}
