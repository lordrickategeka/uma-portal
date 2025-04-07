<?php

namespace Database\Seeders;

use App\Models\UserPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPlan::create([
            'user_id' => 1,
            'plan_id' => 1,
            'subscribed_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);
    }
}
