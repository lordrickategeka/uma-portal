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
        $categories = [
            [
                'name' => 'Medical Officers & SHOs',
                'description' => 'Medical Officers and Senior House Officers category with annual subscription requirements',
                'status' => 'active'
            ],
            [
                'name' => 'Specialists',
                'description' => 'Medical Specialists category with annual subscription requirements',
                'status' => 'active'
            ],
            [
                'name' => 'Intern Doctors',
                'description' => 'Intern Doctors category with one-time membership fee only',
                'status' => 'active'
            ],
            [
                'name' => 'Medical Students',
                'description' => 'Medical Students category with one-time membership fee only',
                'status' => 'active'
            ],
            [
                'name' => 'Life Membership',
                'description' => 'Life Membership category with 20 years subscriptions paid in one installment',
                'status' => 'active'
            ]
        ];

        foreach ($categories as $category) {
            MembershipCategory::create($category);
        }
    }
}
