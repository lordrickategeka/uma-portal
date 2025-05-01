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
    
        // Get user's active installment plans (including completed ones for display)
        $installmentPlans = InstallmentPlan::where('user_id', $user->id)
            ->get()
            ->keyBy('plan_id');
    
        // Get pending transactions for installment plans
        $pendingTransactions = collect();
        foreach ($installmentPlans as $installmentPlan) {
            // Only get pending transactions for non-completed plans
            if ($installmentPlan->status !== 'completed') {
                $pendingTransaction = DB::table('transactions')
                    ->where('user_id', $user->id)
                    ->where('installment_plan_id', $installmentPlan->id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->first();
    
                if ($pendingTransaction) {
                    $pendingTransactions->put($installmentPlan->id, $pendingTransaction);
                }
            }
        }
    
        // Check if user has any ongoing (non-completed) installment
        $hasAnyOngoingInstallment = $installmentPlans->where('status', '!=', 'completed')->isNotEmpty();
    
        return view('dashboard.subscriptions.subscription_plans', compact(
            'plans',
            'userActivePlans',
            'installmentPlans',
            'pendingTransactions',
            'hasAnyOngoingInstallment'
        ));
    }
}
