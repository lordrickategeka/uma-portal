<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $user = Auth::user();
        $isEditor = $user->hasRole('editor');

        $transactionsQuery = Order::with(['user.userPlan', 'user.defaultPaymentMethod.paymentMethod'])
            ->where('payment_status', 'paid');

        if (! $user->hasRole('admin') && ! $user->hasRole('super-admin')) {
            $transactionsQuery->where('user_id', $user->id);
        }

        // Role-based total invoice count
        $invoiceCount = Order::where('payment_status', 'paid');
        $paidSumQuery = Order::where('payment_status', 'paid');

        if (! $user->hasRole('admin') && ! $user->hasRole('super-admin')) {
            $invoiceCount->where('user_id', $user->id);
            $paidSumQuery->where('user_id', $user->id);
        }

        // Base blog query depending on role
        $blogQuery = Blog::query();
        if ($isEditor) {
            $blogQuery->where('author_id', $user->id);
        }

        // Filtered stats
        $totalPosts = (clone $blogQuery)->where('post_type', 'post')->count();
        $pendingPosts = (clone $blogQuery)->where('post_type', 'post')->where('status', 'pending')->count();
        $upcomingEvents = (clone $blogQuery)->where('post_type', 'event')->where('published_at', '>', now())->count();
        $publishedPublications = (clone $blogQuery)->where('post_type', 'publication')->where('status', 'published')->count();

        

        $plan = $user->userPlan; // assuming one active plan per user

        $hasActivePlan = false;

        if ($plan) {
            if ($plan->plan->name === 'Life Member') {
                $hasActivePlan = true;
            } else {
                // Check if we are still within the same year
                $now = Carbon::now();
                $expiresAt = Carbon::create($now->year, 12, 31)->endOfDay();

                $hasActivePlan = $plan->subscribed_at <= $now && $expiresAt >= $now;
            }
        }

        return view('home', [
            'totalPosts' => $totalPosts,
            'pendingPosts' => $pendingPosts,
            'upcomingEvents' => $upcomingEvents,
            'publishedPublications' => $publishedPublications,

            'totalMembers' => User::count(),
            'pendingPayments' => Payment::where('status', 'pending')->count(),
            'totalEarnings' => Payment::where('status', 'successful')->sum('amount'),
            'totalInvoices' => $invoiceCount->count(),
            'totalPaidAmount' => $paidSumQuery->sum('total_amount'),
            'transactions' => $transactionsQuery->latest()->take(10)->get(),

            'hasActivePlan' => $hasActivePlan ? true : false,
        ]);
    }
}
