<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'totalMembers' => User::count(),
            'pendingPayments' => Payment::where('status', 'pending')->count(),
            'totalEarnings' => Payment::where('status', 'successful')->sum('amount'),
            'totalInvoices' => Order::count(),
            'transactions' => Payment::latest()->take(10)->get(),
        ]);
    }
}
