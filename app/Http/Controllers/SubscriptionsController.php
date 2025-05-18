<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionsController extends Controller
{

    public function index()
{
    $user = Auth::user();

    // Get all plans with their membership categories
    $plans = Plan::with('membershipCategory')->get();

    // Get user's active plans (including life memberships)
    $userActivePlans = DB::table('user_plans')
        ->where('user_id', $user->id)
        ->where(function($query) {
            $query->where('expires_at', '>', now())
                  ->orWhereNull('expires_at'); // Life memberships
        })
        ->get()
        ->keyBy('plan_id');

    // Get user's active installment plans
    // Now include all statuses so we can handle cancelled ones in the view
    $installmentPlans = InstallmentPlan::where('user_id', $user->id)
        ->get()
        ->keyBy('plan_id');

    // Get pending transactions for installment plans
    $pendingTransactions = collect();
    foreach ($installmentPlans as $installmentPlan) {
        // Only get pending transactions for active or pending plans
        // Skip plans that are completed or cancelled
        if (!in_array($installmentPlan->status, ['completed', 'cancelled'])) {
            $pendingTransaction = DB::table('transactions')
                ->where('user_id', $user->id)
                ->where('installment_plan_id', $installmentPlan->id)
                ->where('status', 'pending') // Only pending status
                ->orderBy('created_at', 'desc')
                ->first();

            if ($pendingTransaction) {
                $pendingTransactions->put($installmentPlan->id, $pendingTransaction);
            }
        }
    }

    // Check if user has any ongoing (active or pending) installment
    $hasAnyOngoingInstallment = $installmentPlans->whereIn('status', ['active', 'pending'])->isNotEmpty();

    return view('dashboard.subscriptions.subscription_plans', compact(
        'plans',
        'userActivePlans',
        'installmentPlans',
        'pendingTransactions',
        'hasAnyOngoingInstallment'
    ));
}
}
