<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function makeNextPayment($id)
    {
        $user = Auth::user();
        $installmentPlan = InstallmentPlan::findOrFail($id);

        // Ensure the user owns this installment plan
        if ($installmentPlan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Ensure the plan is still active and has remaining payments
        if ($installmentPlan->status === 'completed') {
            return redirect()->back()->with('error', 'This installment plan is already completed');
        }

        if ($installmentPlan->paid_installments >= $installmentPlan->total_installments) {
            return redirect()->back()->with('error', 'All installments have been paid');
        }

        // Calculate the next installment number
        $nextInstallmentNumber = $installmentPlan->paid_installments + 1;

        // Check if there are any pending transactions for this installment plan
        $pendingTransaction = $installmentPlan->transactions()
            ->where('transactions.status', 'pending')
            ->wherePivot('installment_number', $nextInstallmentNumber)
            ->latest()
            ->first();

        if ($pendingTransaction) {
            // If there's a pending transaction, redirect to continue payment
            return redirect()->route('payments.continue', $pendingTransaction->id);
        }

        // Redirect to the payment initialization route with the correct parameters
        return redirect()->route('payments.initialize', [
            'plan_id' => $installmentPlan->plan_id,
            'installment_plan_id' => $installmentPlan->id,
            'installment_number' => $nextInstallmentNumber
        ]);
    }

    /**
     * Display installment plan details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $installmentPlan = InstallmentPlan::findOrFail($id);

        // Ensure the user owns this installment plan
        if ($installmentPlan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $transactions = $installmentPlan->transactions()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the plan details
        $plan = Plan::find($installmentPlan->plan_id);

        return view('installments.show', compact('installmentPlan', 'transactions', 'plan'));
    }

    /**
     * List all installment plans for the user
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $installmentPlans = InstallmentPlan::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.installments.index', compact('installmentPlans'));
    }
}
