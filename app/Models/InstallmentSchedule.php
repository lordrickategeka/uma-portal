<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'status' // pending, paid, overdue
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'installment_schedule_id');
    }

    public function successfulTransaction()
    {
        return $this->transactions()->where('status', 'paid')->latest()->first();
    }
}
