<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\MembershipCategory;
use App\Models\Plan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlansController extends Controller
{
    public function index()
    {
        $plans = Plan::withTrashed()->latest()->paginate(10);
        return view('dashboard.plans.plans', compact('plans'));
    }

    public function create()
    {
        $categories = MembershipCategory::where('status', 'active')->get();
        return view('dashboard.plans.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'membership_category_id' => 'required|exists:membership_categories,id',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,annually,lifetime',
            'auto_renew' => 'boolean',
            'duration' => 'nullable|integer|min:1',
            'benefits' => 'nullable|array',
            'requirements' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        Plan::create($request->all());

        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        $categories = MembershipCategory::where('status', 'active')->get();
        return view('dashboard.plans.edit', compact('plan', 'categories'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'membership_category_id' => 'required|exists:membership_categories,id',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,annually,lifetime',
            'auto_renew' => 'boolean',
            'duration' => 'nullable|integer|min:1',
            'benefits' => 'nullable|array',
            'requirements' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $plan->update($request->all());

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }

    // restore deleted plans 
    public function restore($id)
    {
        $plan = Plan::withTrashed()->find($id);

        if ($plan) {
            $plan->restore();
            return redirect()->route('plans.index')->with('success', 'Plan restored successfully.');
        }

        return redirect()->route('plans.index')->with('error', 'Plan not found.');
    }

   
    public function subscribe(Request $request, $plan_id)
    {
        $plan = Plan::findOrFail($plan_id);
        $user = Auth::user();
        $installmentOption = $request->input('installment_option');
        
        // Check if the plan is a life membership plan
        $isLifeMembership = 
            $plan->membershipCategory->name === 'life' || 
            strtolower($plan->name) === 'life membership';
            
        // If installment option is selected and it's a life membership
        if ($isLifeMembership && $installmentOption && $installmentOption !== 'full') {
            $numberOfInstallments = (int)$installmentOption;
            $amountPerInstallment = ceil($plan->price / $numberOfInstallments);
            
            // Create installment plan
            $installmentPlan = InstallmentPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'total_amount' => $plan->price,
                'amount_per_installment' => $amountPerInstallment,
                'total_installments' => $numberOfInstallments,
                'paid_installments' => 0,
                'remaining_amount' => $plan->price,
                'status' => 'pending',
                'next_payment_date' => Carbon::now()->addDays(30),
            ]);
            
            // Create first transaction for the first installment
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'installment_plan_id' => $installmentPlan->id,
                'plan_id' => $plan->id,
                'amount' => $amountPerInstallment,
                'status' => 'pending',
                'reference_number' => 'INST-' . time() . '-' . rand(1000, 9999),
                'installment_number' => 1
            ]);
            
            // Redirect to payment page with the transaction
            return redirect()->route('payment.page', [
                'plan' => $plan->id,
                'transaction_id' => $transaction->id
            ]);
        }
        
        // For regular subscriptions or full payments
        return redirect()->route('payment.page', [
            'plan' => $plan->id,
            'installment_option' => $installmentOption
        ]);
    }

    public function upgrade($planId)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the user already has an active plan
        $currentPlan = DB::table('user_plans')
            ->where('user_id', $user->id)
            ->where('expires_at', '>', now()) // Check if the plan has not expired
            ->first();

        // If the user has an active plan
        if ($currentPlan) {
            // Fetch the new plan that the user wants to upgrade to
            $newPlan = Plan::find($planId);

            // If the new plan exists and is different from the current plan
            if ($newPlan && $newPlan->id != $currentPlan->plan_id) {
                DB::transaction(function () use ($user, $currentPlan, $newPlan) {
                    // Update the current user's subscription with the new plan
                    DB::table('user_plans')
                        ->where('user_id', $user->id)
                        ->where('plan_id', $currentPlan->plan_id)
                        ->update([
                            'expires_at' => now(), // Expire the current plan
                            'updated_at' => now(),
                        ]);

                    // Insert the new plan into the user_plans table
                    DB::table('user_plans')->insert([
                        'user_id' => $user->id,
                        'plan_id' => $newPlan->id,
                        'subscribed_at' => now(),
                        'expires_at' => now()->addMonth(), // Assuming the plan lasts for a month
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info("User {$user->id} upgraded to plan {$newPlan->id}");
                });

                return redirect()->route('plans.index')->with('success', 'You have successfully upgraded your plan.');
            } else {
                return redirect()->route('plans.index')->with('error', 'Invalid plan or you are already on this plan.');
            }
        } else {
            // If the user has no active plan, show an error
            return redirect()->route('plans.index')->with('error', 'You must have an active plan to upgrade.');
        }
    }
}
