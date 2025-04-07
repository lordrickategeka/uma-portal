<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'status'];

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
