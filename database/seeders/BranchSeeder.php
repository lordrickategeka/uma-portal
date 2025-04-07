<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Main Office',
                'location' => 'Kampala',
                'address' => 'Uganda, Kampala',
                'manager_name' => 'John Doe',
                'email' => 'mainoffice@example.com',
                'phone_number' => '0700000001',
                'code' => 'BR-' . strtoupper(Str::random(6)),
            ],
            [
                'name' => 'Branch A',
                'location' => 'Entebbe',
                'address' => 'Uganda, Kampala',
                'manager_name' => 'Jane Smith',
                'email' => 'brancha@example.com',
                'phone_number' => '0700000002',
                'code' => 'BR-' . strtoupper(Str::random(6)),
            ],
            [
                'name' => 'Branch B',
                'location' => 'Jinja',
                'address' => 'Uganda, Kampala',
                'manager_name' => 'Ali Kimera',
                'email' => 'branchb@example.com',
                'phone_number' => '0700000003',
                'code' => 'BR-' . strtoupper(Str::random(6)),
            ],
            [
                'name' => 'Branch C',
                'location' => 'Mbarara',
                'address' => 'Uganda, Kampala',
                'manager_name' => 'Sarah Aheebwa',
                'email' => 'branchc@example.com',
                'phone_number' => '0700000004',
                'code' => 'BR-' . strtoupper(Str::random(6)),
            ],
            [
                'name' => 'Branch D',
                'location' => 'Gulu',
                'address' => 'Uganda, Kampala',
                'manager_name' => 'Tom Owor',
                'email' => 'branchd@example.com',
                'phone_number' => '0700000005',
                'code' => 'BR-' . strtoupper(Str::random(6)),
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    
    }
}
