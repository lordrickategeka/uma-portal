<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
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

        // 2. Call the artisan command to sync permissions from routes
        Artisan::call('permissions:sync-from-routes');
        echo Artisan::output();

        // 3. Assign all permissions to the super-admin role
        $SuperAdminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Optional: Assign specific permissions to editor
        // $editorRole->syncPermissions([...]);

        $this->command->info('✅ Permissions generated and roles seeded.');
    }
}
