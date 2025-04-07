<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            MembershipCategoriesTableSeeder::class,
            UserProfilesTableSeeder::class,
            PlansTableSeeder::class,
            UserPlansTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            UserPaymentMethodsTableSeeder::class,
            OrdersTableSeeder::class,
            CategorySeeder::class,
            BranchSeeder::class,
            // BlogsSeeder::class,
        ]);
    }
}
