<?php

namespace Database\Seeders;

use App\Models\MembershipCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MembershipCategory::create([
            'name' => 'Standard',
            'description' => 'Basic membership package',
        ]);

        MembershipCategory::create([
            'name' => 'Premium',
            'description' => 'Premium membership package with extra features',
        ]);
    }
}
