<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'code',
    'is_active'
    ];

    public function userPaymentMethods()
    {
        return $this->hasMany(UserPaymentMethod::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
