<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create roles
        $SuperAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // 2. Create the "view-pending-transactions" permission
        $viewPendingTransactionsPermission = Permission::firstOrCreate(['name' => 'view-pending-transactions']);

        // 3. Assign the permission to super-admin
        $SuperAdminRole->givePermissionTo($viewPendingTransactionsPermission);

        // 4. Call the artisan command to sync permissions from routes
        Artisan::call('permissions:sync-from-routes');
        echo Artisan::output();

        // 5. Assign all permissions to the super-admin role
        $SuperAdminRole->syncPermissions(Permission::all());

        // Optional: Assign specific permissions to editor (if needed)
        // $editorRole->syncPermissions([...]);

        $this->command->info('✅ Permissions generated and roles seeded.');
    }
}
