<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Credit Card',
            'code' => 'CC',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name' => 'Mobile Money',
            'code' => 'MM',
            'is_active' => true,
        ]);
    }
}
