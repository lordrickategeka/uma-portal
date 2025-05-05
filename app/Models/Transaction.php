<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'reference',
        'amount',
        'email',
        'name',
        'phone',
        'status',
        'payment_method',
        'payment_link',
        'user_id',
        'plan_id',
        'payment_data',
        'purpose',
         'installment_plan_id',
         'installment_number'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
    ];

    /**
     * Get the user that owns the transaction.
     */
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
        return $this->hasOne(UserPlan::class, 'user_id', 'user_id')->whereColumn('plan_id', 'transactions.plan_id');
    }

    // Many-to-many relationship with installment plans
    public function installmentPlans()
    {
        return $this->belongsToMany(InstallmentPlan::class, 'installment_plan_transactions')
            ->withPivot('installment_number', 'applied_amount')
            ->withTimestamps();
    }

    public function resolveOrAttachInstallmentPlanFromMeta(array $meta)
    {
        $installmentPlan = $this->installmentPlans()->first();

        if (!$installmentPlan && isset($meta['installment_plan_id'])) {
            $installmentPlan = InstallmentPlan::find($meta['installment_plan_id']);

            if ($installmentPlan) {
                $installmentPlan->transactions()->syncWithoutDetaching([
                    $this->id => [
                        'installment_number' => $meta['installment_number'] ?? ($installmentPlan->paid_installments + 1),
                        'applied_amount' => $this->amount,
                    ]
                ]);
            }
        }

        return $installmentPlan;
    }
}
