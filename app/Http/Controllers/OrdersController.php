<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function show(Order $order)
    {
        // Check if order belongs to the current user (if you have authentication)
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Or if you don't have authentication but use sessions:
        // if (session()->get('order_session_id') !== $order->session_id) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Load the order items
        $order->load('items');

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function index()
    {
        // Retrieve orders (you can adjust the query to fit your needs)
        $orders = Order::with(['plan', 'user', 'paymentMethod']) // eager load related models
            ->orderBy('created_at', 'desc') // Sort by the most recent order
            ->paginate(10); // Paginate the results (optional)

        // Return the view with the orders data
        return view('dashboard.transactions.all_transactions', compact('orders'));
    }

    // public function cancel($id)
    // {
    //     $order = Order::findOrFail($id);

    //     // Perform the cancellation logic
    //     $order->update(['payment_status' => 'cancelled']);

    //     // Redirect with a success message
    //     return redirect()->route('orders.index')->with('success', 'Order cancelled successfully.');
    // }
}
