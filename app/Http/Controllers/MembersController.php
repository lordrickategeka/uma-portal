<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\MembershipCategory;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembersController extends Controller
{
    public function index(Request $request)
{
    $query = User::role('member')->with('profile');
    
    // Search functionality
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('first_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('profile', function($profileQuery) use ($searchTerm) {
                  $profileQuery->where('uma_number', 'like', '%' . $searchTerm . '%')
                              ->orWhere('phone', 'like', '%' . $searchTerm . '%');
              });
        });
    }
    
    // Category filter
    if ($request->has('category') && !empty($request->category)) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('membership_category_id', $request->category);
        });
    }
    
    // Branch filter
    if ($request->has('branch') && !empty($request->branch)) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('uma_branch', $request->branch);
        });
    }
    
    // Handle per page
    $perPage = $request->input('per_page', 10);
    
    // Get all active branches for the filter dropdown
    $branches = Branch::where('status', 'active')->get();
    
    // Get all membership categories
    $categories = MembershipCategory::where('status', 'active')->get();
    
    $users = $query->latest()->paginate($perPage)->withQueryString();
    
    return view('dashboard.members.all_members', compact('users', 'branches', 'categories'));
}

    public function activeMembers()
    {
        $now = Carbon::now();
        $expiresAt = Carbon::create($now->year, 12, 31)->endOfDay();

        // We'll use a union approach with pagination
        $lifeMembers = User::whereHas('userPlan.plan', function ($query) {
            $query->where('name', 'Life Member');
        })
            ->with(['profile', 'userPlan.plan'])
            ->select('users.*')
            ->addSelect(DB::raw("'life_member' as member_type"));

        $activeMembers = User::whereHas('userPlan', function ($query) use ($now, $expiresAt) {
            $query->whereHas('plan', function ($planQuery) {
                $planQuery->where('name', '!=', 'Life Member');
            })
                ->where('subscribed_at', '<=', $now)
                ->where('expires_at', '>=', $now);
        })
            ->with(['profile', 'userPlan.plan'])
            ->select('users.*')
            ->addSelect(DB::raw("'regular_member' as member_type"));

        // Combine queries with union and paginate the result
        $allActiveMembers = $lifeMembers->union($activeMembers)
            ->paginate(10);

        return view('dashboard.members.active_members', compact('allActiveMembers'));
    }

    public function inactiveMembers(Request $request)
    {
        $now = Carbon::now();

        // Get users with the 'member' role who either:
        // 1. Don't have any user plan at all, OR
        // 2. Have plans that have expired
        $inactiveMembers = User::role('member')
            ->where(function ($query) use ($now) {
                // Users with no plans
                $query->doesntHave('userPlan')
                    // OR users with expired plans
                    ->orWhereHas('userPlan', function ($planQuery) use ($now) {
                        $planQuery->where('expires_at', '<', $now)
                            // Exclude life members as they don't expire
                            ->whereHas('plan', function ($p) {
                                $p->where('name', '!=', 'Life Member');
                            });
                    });
            })
            ->with(['profile', 'userPlan.plan'])
            ->latest();

        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $inactiveMembers->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('profile', function ($q) use ($searchTerm) {
                        $q->where('uma_number', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Apply filter by expiration status
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status == 'no_plan') {
                $inactiveMembers->doesntHave('userPlan');
            } elseif ($request->status == 'expired') {
                $inactiveMembers->whereHas('userPlan', function ($q) use ($now) {
                    $q->where('expires_at', '<', $now);
                });
            }
        }

        // Get results with pagination
        $inactiveMembers = $inactiveMembers->paginate(10)->withQueryString();

        // Calculate days since expiration for each member
        foreach ($inactiveMembers as $member) {
            if ($member->userPlan && $member->userPlan->expires_at) {
                $expirationDate = Carbon::parse($member->userPlan->expires_at);
                $member->days_since_expiration = $now->diffInDays($expirationDate);
            } else {
                $member->days_since_expiration = null;
            }
        }

        return view('dashboard.members.inactive_members', compact('inactiveMembers'));
    }

    public function show(User $user)
    {
        // Eager load relationships to avoid N+1 queries
        $user->load(['profile', 'userPlan.plan', 'orders']);

        return view('dashboard.members.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('dashboard.members.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // Add logic to check if user can be deleted
        // For example, check if they have any outstanding payments

        $user->delete();

        return redirect()->route('members.active-subscribers')
            ->with('success', 'Member deleted successfully.');
    }

    public function payments(User $user)
    {
        $payments = $user->orders()->paginate(10);

        return view('dashboard.members.payments', compact('user', 'payments'));
    }
}
