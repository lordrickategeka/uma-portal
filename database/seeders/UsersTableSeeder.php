<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'John',
            'email' => 'lordrickategeka@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $role = Role::firstOrCreate(['name' => 'super-admin']);

        // Assign the role
        $user->assignRole($role);
    }
}
