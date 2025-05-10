<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserProfile::create([
            'user_id' => 1,
            'gender' => 'Male',
            'marital_status' => 'Single',
            'age' => 30,
            'phone' => '123456789',
            'address' => '123 Main Street',
            'uma_branch' => 'Central',
            'employer' => 'Company X',
            'category' => 'General',
            'specialization' => 'None',
            'membership_category_id' => 1,
            'next_of_kin' => 'Jane Doe',
            'next_of_kin_phone' => '987654321',
            'referee' => 'Dr. Smith',
            'referee_phone1' => '123123123',
            'referee_phone2' => '456456456',
            'photo' => 'default.png',
            'signature' => 'signature.png',
            'national_id' => 'signature.png',
            'registration_status' => 'pending',
            'uma_number'=> 'UMAOM012345',
        ]);
    }
}
