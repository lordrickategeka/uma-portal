<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'reference_number' => 'ORD123456',
            'payment_tracking_id' => 'PAY456789',
            'payment_status' => 'paid',
            'total_amount' => 99.99,
            'notes' => 'First order payment',
            'user_id' => 1,
            'plan_id' => 1,
            'payment_method_id' => 1,
            'mobile_money_number' => '256789123456',
            'joined_at' => now(),
        ]);
    }
}
