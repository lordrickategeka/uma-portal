<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use Illuminate\Http\Request;

class InstallmentScheduleController extends Controller
{
    private function createInstallmentPlan($user, $plan, $numberOfInstallments)
    {
        $totalAmount = $plan->price;
        $amountPerInstallment = $totalAmount / $numberOfInstallments;

        // Create the main installment plan
        $installmentPlan = InstallmentPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'total_amount' => $totalAmount,
            'amount_per_installment' => $amountPerInstallment,
            'total_installments' => $numberOfInstallments,
            'paid_installments' => 0,
            'remaining_amount' => $totalAmount,
            'next_payment_date' => now(),
            'status' => 'pending'
        ]);

        // Create the installment schedule
        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            // If last installment, use remaining amount to avoid rounding issues
            $amount = ($i == $numberOfInstallments)
                ? $installmentPlan->remaining_amount
                : $amountPerInstallment;

            $dueDate = now()->addDays(($i - 1) * 30);

            InstallmentSchedule::create([
                'installment_plan_id' => $installmentPlan->id,
                'installment_number' => $i,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => $i == 1 ? 'current' : 'pending'
            ]);
        }

        return $installmentPlan;
    }

    // Updated payment verification method
    private function processSuccessfulPayment($transaction, $paymentData)
    {
        $installmentPlan = $transaction->installmentPlan;
        $installmentNumber = $transaction->installment_number;

        // Update the installment plan
        $installmentPlan->paid_installments += 1;
        $installmentPlan->remaining_amount -= $transaction->amount;

        // Update the installment schedule
        $schedule = InstallmentSchedule::where('installment_plan_id', $installmentPlan->id)
            ->where('installment_number', $installmentNumber)
            ->first();

        if ($schedule) {
            $schedule->update([
                'paid_date' => now(),
                'status' => 'paid'
            ]);

            // Set next installment as current
            $nextSchedule = InstallmentSchedule::where('installment_plan_id', $installmentPlan->id)
                ->where('installment_number', $installmentNumber + 1)
                ->first();

            if ($nextSchedule) {
                $nextSchedule->update(['status' => 'current']);
                $installmentPlan->next_payment_date = $nextSchedule->due_date;
            }
        }

        // If all installments are paid
        if ($installmentPlan->paid_installments >= $installmentPlan->total_installments) {
            $installmentPlan->status = 'completed';
            $installmentPlan->remaining_amount = 0;

            // Activate the subscription
            $this->activateSubscription($transaction->user, $transaction->plan);
        } else {
            $installmentPlan->status = 'active';
        }

        $installmentPlan->save();
    }
}
