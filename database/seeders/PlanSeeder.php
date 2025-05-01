<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\MembershipCategory;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            // Medical Officers & SHOs Plans
            [
                'category' => 'Medical Officers & SHOs',
                'name' => 'Medical Officers & SHOs Annual Subscription',
                'description' => 'Annual subscription fee for Medical Officers and Senior House Officers',
                'price' => '100000',
                'billing_cycle' => 'annually',
                'auto_renew' => true,
                'duration' => 365,
                'benefits' => [
                    'Continued access to all member benefits',
                    'Annual CPD certification',
                    'Priority access to UMA-PP events',
                    'Discounted rates for conferences'
                ],
                'requirements' => [
                    'Active membership status',
                    'Current practice license'
                ],
                'status' => 'active'
            ],

            // Specialists Plans
            [
                'category' => 'Specialists',
                'name' => 'Specialists Annual Subscription',
                'description' => 'Annual subscription fee for Medical Specialists',
                'price' => '100000',
                'billing_cycle' => 'annually',
                'auto_renew' => true,
                'duration' => 365,
                'benefits' => [
                    'Continued access to all specialist benefits',
                    'Annual specialist CPD certification',
                    'Priority access to specialist forums',
                    'International conference opportunities'
                ],
                'requirements' => [
                    'Active membership status',
                    'Current specialist license'
                ],
                'status' => 'active'
            ],

            // Intern Doctors Plan
            [
                'category' => 'Intern Doctors',
                'name' => 'Intern Doctors Membership',
                'description' => 'One-time membership fee for Intern Doctors (No annual subscription required)',
                'price' => '50000',
                'billing_cycle' => 'No_Annual_Fee',
                'auto_renew' => false,
                'duration' => null,
                'benefits' => [
                    'Access to training resources',
                    'Mentorship programs',
                    'Career guidance',
                    'Networking with senior doctors'
                ],
                'requirements' => [
                    'Must be a registered intern doctor',
                    'Internship placement verification',
                    'Medical school graduation certificate'
                ],
                'status' => 'active'
            ],

            // Medical Students Plan
            [
                'category' => 'Medical Students',
                'name' => 'Medical Students Membership',
                'description' => 'One-time membership fee for Medical Students (No annual subscription required)',
                'price' => '10000',
                'billing_cycle' => 'No_Annual_Fee',
                'auto_renew' => false,
                'duration' => null,
                'benefits' => [
                    'Access to educational resources',
                    'Student mentorship programs',
                    'Career planning workshops',
                    'Student networking events'
                ],
                'requirements' => [
                    'Must be enrolled in a medical school',
                    'Student ID verification',
                    'University enrollment letter'
                ],
                'status' => 'active'
            ],

            // Life Membership Plan
            [
                'category' => 'Life Membership',
                'name' => 'Life Membership',
                'description' => 'Life membership with 20 years subscriptions paid in one installment',
                'price' => '2000000',
                'billing_cycle' => 'lifetime',
                'auto_renew' => false,
                'duration' => 7300, // 20 years in days
                'benefits' => [
                    'Full access to all UMA-PP resources for 20 years',
                    'Lifetime member recognition',
                    'VIP access to all events',
                    'Priority services',
                    'Special life member benefits'
                ],
                'requirements' => [
                    'Must be eligible for full membership',
                    'Valid medical license',
                    'Professional standing verification'
                ],
                'status' => 'active'
            ]
        ];

        foreach ($plans as $planData) {
            $category = MembershipCategory::where('name', $planData['category'])->first();
            
            if ($category) {
                Plan::create([
                    'name' => $planData['name'],
                    'description' => $planData['description'],
                    'membership_category_id' => $category->id,
                    'price' => $planData['price'],
                    'billing_cycle' => $planData['billing_cycle'],
                    'auto_renew' => $planData['auto_renew'],
                    'duration' => $planData['duration'],
                    'benefits' => $planData['benefits'],
                    'requirements' => $planData['requirements'],
                    'status' => $planData['status']
                ]);
            }
        }
    }
}