<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'payment_tracking_id',
        'confirmation_code',
        'payment_status',
        'total_amount',
        'currency',
        'notes',
        'joined_at',
        'user_id',
        'plan_id',
        'payment_method_id',
        'mobile_money_number',
        'payment_status_code',
        'payment_account',
        'call_back_url',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function userPlan()
    {
        return $this->hasOne(UserPlan::class, 'user_id', 'user_id')->whereColumn('plan_id', 'orders.plan_id');
    }
       
    
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
