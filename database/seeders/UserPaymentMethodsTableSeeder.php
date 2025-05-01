<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\UserPaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPaymentMethod::create([
            'user_id' => 1,
            'payment_method_id' => 1,
            'account_number' => '0753901870',
            'is_default' => true,
        ]);
    }
}
