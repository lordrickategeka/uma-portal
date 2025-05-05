<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        // Base query with necessary joins
        $query = Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'transactions.plan_id')
            ->select(
                'transactions.*',
                'users.first_name',
                'users.last_name',
                'users.email',
                'user_profiles.phone',
                'user_profiles.uma_number',
                'plans.name as plan_name'
            );

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('users.first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('transactions.reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('user_profiles.uma_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('user_profiles.phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('transactions.status', $request->status);
        } else {
            // Check user permissions for viewing pending transactions
            if (!Auth::user()->can('view-pending-transactions')) {
                $query->where('transactions.status', '!=', 'pending');
            }
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('transactions.payment_method', $request->payment_method);
        }

        // Filter by plan
        if ($request->has('plan_id') && !empty($request->plan_id)) {
            $query->where('transactions.plan_id', $request->plan_id);
        }

        // Handle per page
        $perPage = $request->input('per_page', 10);

        // Get all plans for the filter dropdown
        $plans = Plan::where('status', 'active')->get();

        // Get the filtered transactions with pagination
        $transactions = $query->latest('transactions.created_at')->paginate($perPage)->withQueryString();

        return view('dashboard.transactions.all_transactions', compact('transactions', 'plans'));
    }

    public function show(Transaction $transaction)
    {
        // No need to call findOrFail since model binding already does this
        $transaction->load([
            'user',
            'user.profile',
            'plan'
        ]);

        // Manually load the userPlan to avoid the SQL error
        $userPlan = UserPlan::where('user_id', $transaction->user_id)
            ->where('plan_id', $transaction->plan_id)
            ->first();

        $transaction->setRelation('userPlan', $userPlan);

        // Check if the current user has permission
        if (
            !Auth::user()->can('view-transactions') &&
            (Auth::user()->id != $transaction->user_id)
        ) {
            abort(403, 'Unauthorized action.');
        }

        return view('dashboard.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        // Base query with necessary joins - similar to index method
        $query = Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoin('plans', 'plans.id', '=', 'transactions.plan_id')
            ->select(
                'transactions.id',
                'transactions.reference',
                'transactions.amount',
                'transactions.currency',
                'transactions.payment_method',
                'transactions.status',
                'transactions.created_at',
                'transactions.payment_date',
                'users.first_name', 
                'users.last_name',
                'users.email',
                'user_profiles.phone',
                'user_profiles.uma_number',
                'plans.name as plan_name'
            );
    
        // Apply the same filters as in the index method
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('users.last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('users.email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('transactions.reference', 'like', '%' . $searchTerm . '%')
                  ->orWhere('user_profiles.uma_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('user_profiles.phone', 'like', '%' . $searchTerm . '%');
            });
        }
    
        if ($request->has('status') && !empty($request->status)) {
            $query->where('transactions.status', $request->status);
        } elseif (!Auth::user()->can('view-pending-transactions')) {
            $query->where('transactions.status', '!=', 'pending');
        }
    
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('transactions.payment_method', $request->payment_method);
        }
    
        if ($request->has('plan_id') && !empty($request->plan_id)) {
            $query->where('transactions.plan_id', $request->plan_id);
        }
    
        // Get all transactions for export
        $transactions = $query->latest('transactions.created_at')->get();
    
        // Get current timestamp for filename
        $timestamp = now()->format('Y-m-d-H-i-s');
        $filename = "transactions-export-{$timestamp}";
    
        // Handle different export formats
        $format = $request->input('format', 'csv');
    
        if ($format === 'csv') {
            return $this->exportToCSV($transactions, $filename);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($transactions, $filename);
        }
    
        // Default to CSV if no valid format is provided
        return $this->exportToCSV($transactions, $filename);
    }

    /**
     * Export transactions to CSV file
     */
    private function exportToCSV($transactions, $filename)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Reference',
                'UMA Number',
                'Member Name',
                'Email',
                'Phone',
                'Plan',
                'Amount',
                'Payment Method',
                'Status',
                'Transaction Date',
                'Payment Date'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->reference,
                    $transaction->uma_number ?? 'N/A',
                    $transaction->first_name . ' ' . $transaction->last_name,
                    $transaction->email,
                    $transaction->phone ?? 'N/A',
                    $transaction->plan_name ?? 'N/A',
                    number_format($transaction->amount, 2) . ' ' . $transaction->currency,
                    $transaction->payment_method,
                    ucfirst($transaction->status),
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->payment_date ? \Carbon\Carbon::parse($transaction->payment_date)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export transactions to PDF file
     */
    private function exportToPDF($transactions, $filename)
    {
        // Load the PDF package - make sure you have installed it
        // composer require barryvdh/laravel-dompdf
        $pdf = app()->make('dompdf.wrapper');

        // Get organization details for the header
        $organizationName = config('app.name', 'UMA Membership System');
        $currentDate = now()->format('F d, Y');

        // Prepare the HTML content
        $html = view('dashboard.transactions.export_pdf', compact('transactions', 'organizationName', 'currentDate'))->render();

        // Load HTML to PDF
        $pdf->loadHTML($html);

        // Optional: Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        // Download the PDF file
        return $pdf->download("{$filename}.pdf");
    }
}
