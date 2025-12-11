<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'user-list', 'user-create', 'user-edit', 'user-delete',

            // Customer permissions
            'customer-list', 'customer-create', 'customer-edit', 'customer-delete',

            // Project permissions
            'project-list', 'project-create', 'project-edit', 'project-delete',

            // Task permissions
            'task-list', 'task-create', 'task-edit', 'task-delete',

            // Order permissions
            'order-list', 'order-create', 'order-edit', 'order-delete',

            // Finance permissions
            'finance-list', 'finance-create', 'finance-edit', 'finance-delete',

            // Role permissions
            'role-list', 'role-create', 'role-edit', 'role-delete',

            // Report permissions
            'report-view',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - Full access
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Manager
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'order-list', 'order-create', 'order-edit', // No delete
            'project-list', 'project-create', 'project-edit', 'project-delete',
            'task-list', 'task-create', 'task-edit', 'task-delete',
            'report-view',
        ]);

        // Staff
        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo([
            'project-list', 'project-edit', // Only assigned projects
            'task-list', 'task-create', 'task-edit', 'task-delete',
        ]);

        // Finance
        $finance = Role::create(['name' => 'Finance']);
        $finance->givePermissionTo([
            'finance-list', 'finance-create', 'finance-edit', 'finance-delete',
            'project-list', // Read only
            'order-list', 'order-edit', // No delete
        ]);
    }
}
