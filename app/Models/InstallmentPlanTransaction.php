<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlanTransaction extends Model
{
    protected $table = 'installment_plan_transactions';

    protected $fillable = [
        'installment_plan_id',
        'transaction_id',
        'installment_number',
        'applied_amount',
    ];

    protected $casts = [
        'applied_amount' => 'decimal:2',
    ];

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
