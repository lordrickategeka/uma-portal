<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InstallmentPlan extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'total_amount',
        'amount_per_installment',
        'total_installments',
        'paid_installments',
        'amount_paid',
        'remaining_amount',
        'next_payment_date',
        'status'
    ];

    protected $casts = [
        'next_payment_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'amount_per_installment' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * Get the user that owns this installment plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for this installment plan.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the transactions associated with this installment plan.
     */
    public function transactions()
{
    return $this->belongsToMany(Transaction::class, 'installment_plan_transactions')
        ->withPivot('installment_number', 'applied_amount')
        ->withTimestamps();
}

    /**
     * Update payment stats for this installment plan
     */
    public function updatePaymentStats()
    {
        // Get all paid transactions for this installment plan
        $paidTransactions = $this->transactions()
            ->where('status', 'paid')
            ->get();

        // Calculate total paid amount
        $totalPaid = $paidTransactions->sum(function ($transaction) {
            return $transaction->pivot->applied_amount;
        });

        // Update installment plan
        $this->paid_installments = $paidTransactions->count();
        $this->amount_paid = $totalPaid;
        $this->remaining_amount = $this->total_amount - $totalPaid;

        // Update status
        if ($this->paid_installments >= $this->total_installments || $this->remaining_amount <= 0) {
            $this->status = 'completed';
        } else {
            $this->status = 'active';
            // Set next payment date if not completed
            $this->next_payment_date = now()->addDays(30);
        }

        $this->save();

        return $this;
    }

    /**
     * Check if the installment plan is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' ||
            $this->paid_installments >= $this->total_installments ||
            $this->remaining_amount <= 0;
    }

    /**
     * Get the next installment number
     */
    public function getNextInstallmentNumber(): int
    {
        return $this->paid_installments + 1;
    }

    /**
     * Calculate the amount for the next installment
     */
    public function getNextInstallmentAmount(): float
    {
        if ($this->isCompleted()) {
            return 0;
        }

        // If this is the last installment, return the remaining amount
        if ($this->getNextInstallmentNumber() == $this->total_installments) {
            return $this->remaining_amount;
        }

        // Otherwise, return the standard amount per installment
        return $this->amount_per_installment;
    }
}
