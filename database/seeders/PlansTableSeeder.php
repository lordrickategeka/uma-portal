<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Basic Plan',
            'description' => 'Affordable membership plan',
            'membership_category_id' => 1,
            'price' => 99.99,
            'billing_cycle' => 'monthly',
            'auto_renew' => true,
            'duration' => 30,
            'benefits' => json_encode(['Priority support', 'Discounts']),
            'requirements' => json_encode(['Valid ID']),
            'status' => 'active',
        ]);
    }
}
