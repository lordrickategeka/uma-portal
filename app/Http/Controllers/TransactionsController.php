<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index()
    {
        $orders = Order::leftJoin('user_plans', function($join) {
            $join->on('user_plans.user_id', '=', 'orders.user_id')
                 ->on('user_plans.plan_id', '=', 'orders.plan_id');
        })
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->leftJoin('plans', 'plans.id', '=', 'orders.plan_id')
        ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
        ->select(
            'orders.*', 
            'user_plans.subscribed_at', 
            'user_plans.expires_at',
            'users.first_name',
            'users.last_name',
            'plans.name as plan_name',
            'payment_methods.name as payment_method_name'
        )
        ->paginate(12);
    

        return view('dashboard.transactions.all_transactions', compact('orders'));
    }
}
