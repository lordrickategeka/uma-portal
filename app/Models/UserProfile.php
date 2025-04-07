<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'gender', 'marital_status', 'age', 'phone', 'address',
        'uma_branch', 'employer', 'category', 'specialization', 'umdpc_number',
        'membership_category_id', 'next_of_kin', 'next_of_kin_phone',
        'referee', 'referee_phone1', 'referee_phone2', 'photo', 'signature',
        'national_id', 'license_document', 'registration_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membershipCategory()
    {
        return $this->belongsTo(MembershipCategory::class, 'membership_category_id');
    }
}
