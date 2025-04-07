<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'membership_category_id', 'price', 'billing_cycle',
        'auto_renew', 'duration', 'benefits', 'requirements', 'status'
    ];

    protected $casts = [
        'auto_renew' => 'boolean',
        'benefits' => 'array',
        'requirements' => 'array',
    ];

    public function membershipCategory()
    {
        return $this->belongsTo(MembershipCategory::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
