<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'uma_number',
        'gender',
        'marital_status',
        'age',
        'phone',
        'address',
        'uma_branch',
        'employer',
        'category',
        'specialization',
        'umdpc_number',
        'membership_category_id',
        'next_of_kin',
        'next_of_kin_phone',
        'referee',
        'referee_phone1',
        'referee_phone2',
        'photo',
        'signature',
        'national_id',
        'license_document',
        'registration_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membershipCategory()
    {
        return $this->belongsTo(MembershipCategory::class, 'membership_category_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'uma_branch', 'name');
    }

    public static function generateUMANumber()
    {
        // Get the latest profile with a UMA number to determine the next number
        $latestProfile = self::whereNotNull('uma_number')
            ->orderBy('id', 'desc')
            ->first();

        // If no profiles have a UMA number yet, start with 000001
        $nextNumber = '000001';

        if ($latestProfile) {
            // Extract the numeric part of the UMA number
            $numericPart = substr($latestProfile->uma_number, 5); // Skip "UMAOM"
            // Increment and pad with leading zeros
            $nextNumber = str_pad((int)$numericPart + 1, 6, '0', STR_PAD_LEFT);
        }

        return 'UMAOM' . $nextNumber;
    }  
    
}
