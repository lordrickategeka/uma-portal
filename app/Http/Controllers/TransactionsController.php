<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    // public function index()
    // {
    //     // Check if the user has permission to view 'pending' transactions
    //     if (Auth::user()->can('view-pending-transactions')) {
    //         // If the user has permission, fetch all orders, including 'pending' status
    //         $orders = Order::leftJoin('user_plans', function ($join) {
    //             $join->on('user_plans.user_id', '=', 'orders.user_id')
    //                 ->on('user_plans.plan_id', '=', 'orders.plan_id');
    //         })
    //             ->leftJoin('users', 'users.id', '=', 'orders.user_id')
    //             ->leftJoin('plans', 'plans.id', '=', 'orders.plan_id')
    //             ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
    //             ->select(
    //                 'orders.*',
    //                 'user_plans.subscribed_at',
    //                 'user_plans.expires_at',
    //                 'users.first_name',
    //                 'users.last_name',
    //                 'plans.name as plan_name',
    //                 'payment_methods.name as payment_method_name'
    //             )
    //             ->whereIn('orders.payment_status', ['pending', 'paid']) // Include 'pending' status
    //             ->paginate(12);
    //     } else {
    //         // If the user does not have permission, fetch only non-pending orders
    //         $orders = Order::leftJoin('user_plans', function ($join) {
    //             $join->on('user_plans.user_id', '=', 'orders.user_id')
    //                 ->on('user_plans.plan_id', '=', 'orders.plan_id');
    //         })
    //             ->leftJoin('users', 'users.id', '=', 'orders.user_id')
    //             ->leftJoin('plans', 'plans.id', '=', 'orders.plan_id')
    //             ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
    //             ->select(
    //                 'orders.*',
    //                 'user_plans.subscribed_at',
    //                 'user_plans.expires_at',
    //                 'users.first_name',
    //                 'users.last_name',
    //                 'plans.name as plan_name',
    //                 'payment_methods.name as payment_method_name'
    //             )
    //             ->where('orders.payment_status', '!=', 'pending') // Exclude 'pending' status
    //             ->paginate(12);
    //     }

    //     return view('dashboard.transactions.all_transactions', compact('orders'));
    // }

    public function index()
{
    // Check if the user has permission to view 'pending' transactions
    if (Auth::user()->can('view-pending-transactions')) {
        // If the user has permission, fetch all transactions, including 'pending' status
        $transactions = Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'transactions.plan_id')
            ->select(
                'transactions.*',
                'users.first_name as user_name',
                'plans.name as plan_name'
            )
            ->whereIn('transactions.status', ['pending', 'paid']) // Include 'pending' status
            ->paginate(12);
    } else {
        // If the user does not have permission, fetch only non-pending transactions
        $transactions = Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'transactions.plan_id')
            ->select(
                'transactions.*',
                'users.first_name as user_name',
                'plans.name as plan_name'
            )
            ->where('transactions.status', '!=', 'pending') // Exclude 'pending' status
            ->paginate(12);
    }

    return view('dashboard.transactions.all_transactions', compact('transactions'));
}
}
