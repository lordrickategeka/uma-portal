<?php

namespace App\Http\Controllers;

use App\Mail\InstallmentPaymentMail;
use App\Mail\SubscriptionActivatedMail;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subaccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FlutterPaymentController extends Controller
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $encryptionKey;
    protected $callbackUrl;

    public function __construct()
    {
        // Load configuration from environment variables
        $this->baseUrl = env('FLW_BASE_URL', 'https://api.flutterwave.com/v3');
        $this->secretKey = env('FLW_SECRET_KEY');
        $this->publicKey = env('FLW_PUBLIC_KEY');
        $this->encryptionKey = env('FLW_ENCRYPTION_KEY');

        // Set callback URL based on environment
        $this->callbackUrl = app()->environment('production')
            ? config('app.url') . '/payments/callback'
            : env('FLUTTERWAVE_TEST_CALLBACK_URL', 'https://2c7f-102-134-149-88.ngrok-free.app/payments/callback');
    }

    public function initializePaymentFlutter(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to subscribe.');
        }

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Check if this is a life membership plan
        $isLifeMembership = $plan->membershipCategory->name === 'life' ||
            strtolower($plan->name) === 'life membership';

        // Check if this is an installment payment
        $installmentOption = $request->installment_option ?? 'full';
        $installmentPlanId = $request->installment_plan_id ?? null;
        $installmentNumber = $request->installment_number ?? null;
        $installmentPlan = null;
        $totalAmount = 0;

        // If this is a continuation of an existing installment plan
        if ($installmentPlanId) {
            $installmentPlan = InstallmentPlan::findOrFail($installmentPlanId);

            // Ensure this user owns this installment plan
            if ($installmentPlan->user_id !== $user->id) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }

            // Get the current installment number
            $currentInstallmentNumber = $installmentPlan->paid_installments + 1;

            // Ensure we're paying the correct installment
            if ($installmentNumber && $installmentNumber != $currentInstallmentNumber) {
                return redirect()->back()->with('error', 'Invalid installment number');
            }

            // Calculate the correct amount for this installment
            if ($currentInstallmentNumber == $installmentPlan->total_installments) {
                // For the last installment, use the exact remaining amount
                $totalAmount = $installmentPlan->remaining_amount;
                Log::info("Last installment payment: Using exact remaining amount: {$totalAmount}");
            } else {
                // For other installments, use the standard amount per installment
                $totalAmount = $installmentPlan->amount_per_installment;
                Log::info("Regular installment payment: Using amount per installment: {$totalAmount}");
            }

            // Set the installment number
            $installmentNumber = $currentInstallmentNumber;
        }
        // If this is a new installment plan
        elseif ($isLifeMembership && $installmentOption !== 'full') {
            $numberOfInstallments = (int) $installmentOption;

            // Ensure valid installment option
            if (!in_array($numberOfInstallments, [2, 3, 4])) {
                return redirect()->back()->with('error', 'Invalid installment option');
            }

            // Calculate amount per installment
            $amountPerInstallment = round($plan->price / $numberOfInstallments, 2);
            $totalAmount = $amountPerInstallment; // First installment

            // Create the installment plan
            $installmentPlan = InstallmentPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'total_amount' => $plan->price,
                'amount_per_installment' => $amountPerInstallment,
                'total_installments' => $numberOfInstallments,
                'paid_installments' => 0,
                'amount_paid' => 0,  // Start with 0 paid
                'remaining_amount' => $plan->price,
                'next_payment_date' => now()->addDays(30),
                'status' => 'pending'
            ]);

            $installmentPlanId = $installmentPlan->id;
            $installmentNumber = 1;

            Log::info("Created new installment plan: {$installmentPlan->id}, Amount per installment: {$amountPerInstallment}, Total: {$plan->price}");
        }
        // Regular full payment
        else {
            $totalAmount = $plan->price;
            Log::info("Full payment: {$totalAmount}");
        }

        // Generate a unique transaction reference if not provided
        $reference = $request->reference ?? $this->generateReference();

        // Save transaction to database
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'installment_plan_id' => $installmentPlanId,
            'installment_number' => $installmentNumber,
            'reference' => $reference,
            'amount' => $totalAmount,
            'email' => $user->email,
            'name' => $user->first_name,
            'phone' => $user->profile->phone ?? '',
            'status' => 'pending',
            'purpose' => $installmentPlanId ? 'installment' : 'full_payment',
        ]);

        Log::info("New transaction created: ID: {$transaction->id}, Amount: {$totalAmount}, Installment Plan: {$installmentPlanId}, Installment Number: {$installmentNumber}");

        // Prepare payment payload
        $payload = $this->buildPaymentPayload($transaction, $user, $plan, $installmentPlanId, $installmentNumber);

        // Make API request to Flutterwave
        try {
            // Log the payload for debugging
            Log::info('Using callback URL: ' . $this->callbackUrl);
            Log::info('Flutterwave payment payload:', $payload);

            $response = Http::timeout(40)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/payments', $payload);

            $responseData = $response->json();

            // Log the full response
            Log::info('Flutterwave payment response: ' . json_encode($responseData));

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === 'success') {
                // Update transaction with payment link
                $this->updateTransactionWithPaymentLink($transaction, $responseData['data']['link']);
                return redirect()->away($responseData['data']['link']);
            }

            return response()->json([
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Failed to initialize payment',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Flutterwave payment initialization error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your payment',
            ], 500);
        }
    }

    private function buildPaymentPayload($transaction, $user, $plan, $installmentPlanId, $installmentNumber)
    {
        return [
            'tx_ref' => $transaction->reference,
            'amount' => $transaction->amount,
            'currency' => 'UGX',
            'payment_options' => 'card, banktransfer, ussd, mobile_money',
            'redirect_url' => $this->callbackUrl,
            'customer' => [
                'email' => $user->email,
                'name' => $user->first_name,
                'phone_number' => $user->profile->phone ?? '',
            ],
            'meta' => [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'installment_plan_id' => $installmentPlanId,
                'installment_number' => $installmentNumber,
            ],
            'customizations' => [
                'title' => config('app.name') . ' Payment',
                'description' => isset($installmentPlanId)
                    ? "Installment {$installmentNumber} for {$plan->name}"
                    : "Subscription for {$plan->name}",
                'logo' => config('flutterwave.logo_url', ''),
            ],
            'subaccounts' => [
                [
                    'id' => 'RS_A336B19DEEBF018ECFF27EEA6023BC05', // Test subaccount ID
                    'transaction_charge_type' => 'flat',
                    'transaction_charge' => 500
                ]
            ],
        ];
    }

    public function verifyTransaction($reference)
    {
        try {
            $response = Http::timeout(40)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])->get($this->baseUrl . '/transactions/verify_by_reference?tx_ref=' . $reference);

            $responseData = $response->json();

            if (
                $response->successful() &&
                isset($responseData['status']) &&
                $responseData['status'] === 'success' &&
                isset($responseData['data']['status']) &&
                $responseData['data']['status'] === 'successful'
            ) {
                $data = $responseData['data'];
                $txRef = $data['tx_ref'];
                $paymentMethod = $data['payment_type'] ?? 'Unknown';

                $transaction = Transaction::where('reference', $txRef)->first();

                if (!$transaction) {
                    Log::error("Transaction with reference {$txRef} not found.");
                    return ['verified' => false, 'error' => 'Transaction not found'];
                }

                DB::transaction(function () use ($transaction, $data, $paymentMethod) {
                    // Update transaction status
                    $transaction->update([
                        'status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_data' => json_encode($data),
                    ]);

                    $user = $transaction->user;
                    $plan = $transaction->plan;

                    if (!$plan) {
                        Log::error("Transaction ID {$transaction->id} has no associated plan.");
                        throw new \Exception("Missing plan for transaction {$transaction->id}");
                    }

                    // Check if this transaction is associated with an installment plan
                    // Check if installment_plan_id is set on the transaction
                    if ($transaction->installment_plan_id) {
                        $installmentPlan = InstallmentPlan::find($transaction->installment_plan_id);

                        if (!$installmentPlan) {
                            Log::error("Installment plan {$transaction->installment_plan_id} not found for transaction {$transaction->id}");
                            throw new \Exception("Installment plan not found");
                        }

                        $installmentNumber = $transaction->installment_number ?? ($installmentPlan->paid_installments + 1);

                        Log::info("Processing installment payment for transaction {$transaction->id}, installment plan {$installmentPlan->id}, installment number {$installmentNumber}");

                        // Attach to pivot table if not already attached
                        if (!$transaction->installmentPlans()->where('installment_plan_id', $installmentPlan->id)->exists()) {
                            $installmentPlan->transactions()->attach($transaction->id, [
                                'installment_number' => $installmentNumber,
                                'applied_amount' => $transaction->amount
                            ]);
                        }

                        // Recalculate paid stats
                        $allTransactions = $installmentPlan->transactions()->where('status', 'paid')->get();
                        $totalPaid = $allTransactions->sum('pivot.applied_amount');
                        $paidInstallments = $allTransactions->count();

                        $installmentPlan->paid_installments = $paidInstallments;
                        $installmentPlan->amount_paid = $totalPaid;
                        $installmentPlan->remaining_amount = $installmentPlan->total_amount - $totalPaid;

                        // Determine plan status
                        if ($paidInstallments >= $installmentPlan->total_installments || $installmentPlan->remaining_amount <= 0) {
                            $installmentPlan->status = 'completed';
                            Log::info("Installment plan {$installmentPlan->id} marked as completed");
                        } else {
                            $installmentPlan->status = 'active';
                            $installmentPlan->next_payment_date = now()->addDays(30);
                            Log::info("Installment plan {$installmentPlan->id} active, next payment: {$installmentPlan->next_payment_date}");
                        }

                        $installmentPlan->save();

                        if ($installmentPlan->status === 'completed') {
                            $currentDate = now()->format('Y-m-d H:i:s');
                            $expiryDate = $plan->membershipCategory->name === 'life' ? null : now()->addYear()->format('Y-m-d H:i:s');

                            DB::table('user_plans')->updateOrInsert(
                                ['user_id' => $user->id, 'plan_id' => $plan->id],
                                [
                                    'subscribed_at' => $currentDate,
                                    'expires_at' => $expiryDate,
                                    'updated_at' => $currentDate
                                ]
                            );

                            Mail::to($user->email)->send(new SubscriptionActivatedMail(
                                $user,
                                $plan,
                                $transaction->reference,
                                $paymentMethod
                            ));

                            Log::info("Life membership activated for user {$user->id}, plan {$plan->id}, expires: {$expiryDate}");
                        } else {
                            Mail::to($user->email)->send(new InstallmentPaymentMail(
                                $user,
                                $plan,
                                $transaction->reference,
                                $paymentMethod,
                                $installmentPlan,
                                $installmentNumber
                            ));

                            Log::info("Installment {$installmentNumber} paid for user {$user->id}, plan {$plan->id}. Remaining: {$installmentPlan->remaining_amount}");
                        }
                    } else {
                        // Full one-time payment
                        Log::info("Processing full payment for transaction {$transaction->id}");

                        $currentDate = now()->format('Y-m-d H:i:s');
                        $expiryDate = $plan->membershipCategory->name === 'life' ? null :
                            now()->addYear()->format('Y-m-d H:i:s');

                        DB::table('user_plans')->updateOrInsert(
                            ['user_id' => $user->id, 'plan_id' => $plan->id],
                            [
                                'subscribed_at' => $currentDate,
                                'expires_at' => $expiryDate,
                                'updated_at' => $currentDate
                            ]
                        );

                        Mail::to($user->email)->send(new SubscriptionActivatedMail(
                            $user,
                            $plan,
                            $transaction->reference,
                            $paymentMethod
                        ));

                        Log::info("Subscription activated for user {$user->id}, plan {$plan->id}, expires: {$expiryDate}");
                    }
                });

                return ['verified' => true, 'data' => $data];
            }

            return ['verified' => false, 'message' => 'Verification failed', 'data' => $responseData];
        } catch (\Exception $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());
            return ['verified' => false, 'message' => 'Error during verification'];
        }
    }

    public function continuePayment($transaction_id)
    {
        try {
            // Find the original transaction
            $originalTransaction = Transaction::findOrFail($transaction_id);
            $user = Auth::user();

            // Ensure this user owns this transaction
            if ($originalTransaction->user_id !== $user->id) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }

            // Only allow continuing pending transactions
            if ($originalTransaction->status !== 'pending') {
                return redirect()->back()->with('error', 'This transaction is no longer pending');
            }

            // Get the plan and installment plan details
            $plan = $originalTransaction->plan;
            $installmentPlanId = $originalTransaction->installment_plan_id;
            $installmentNumber = $originalTransaction->installment_number;

            // Get the correct amount
            $amount = $originalTransaction->amount;

            // Mark the original transaction as abandoned
            $originalTransaction->status = 'abandoned';
            $originalTransaction->save();

            Log::info("Original transaction {$transaction_id} marked as abandoned. Creating new transaction.");

            // Create a new transaction record
            $newReference = $this->generateReference();

            $newTransaction = Transaction::create([
                'user_id' => $user->id,
                'plan_id' => $originalTransaction->plan_id,
                'installment_plan_id' => $installmentPlanId,
                'installment_number' => $installmentNumber,
                'reference' => $newReference,
                'amount' => $amount,
                'email' => $user->email,
                'name' => $user->name ?? $user->first_name,
                'phone' => $user->profile->phone ?? '',
                'status' => 'pending',
            ]);

            Log::info("New transaction created: {$newTransaction->id} for amount: {$amount}");

            // Get the callback URL based on environment
            $callbackUrl = app()->environment('production')
                ? config('app.url') . '/payments/callback'
                : env('FLUTTERWAVE_TEST_CALLBACK_URL', 'https://91b6-41-210-145-1.ngrok-free.app/payments/callback');

            // Prepare the payment payload
            $payload = [
                'tx_ref' => $newReference,
                'amount' => $amount,
                'currency' => 'UGX',
                'payment_options' => 'card, banktransfer, ussd, mobile_money',
                'redirect_url' => $callbackUrl,
                'customer' => [
                    'email' => $user->email,
                    'name' => $user->name ?? $user->first_name,
                    'phone_number' => $user->profile->phone ?? '',
                ],
                'meta' => [
                    'transaction_id' => $newTransaction->id,
                    'user_id' => $user->id,
                    'installment_plan_id' => $installmentPlanId,
                    'installment_number' => $installmentNumber,
                    'continuation_of' => $transaction_id,
                ],
                'customizations' => [
                    'title' => config('app.name') . ' Payment',
                    'description' => isset($installmentPlanId)
                        ? "Installment {$installmentNumber} for {$plan->name}"
                        : "Subscription for {$plan->name}",
                    'logo' => env('APP_LOGO', ''),
                ],
                'subaccounts' => [
                    [
                        'id' => 'RS_A336B19DEEBF018ECFF27EEA6023BC05', // Test subaccount ID
                        'transaction_charge_type' => 'flat',
                        'transaction_charge' => 500
                    ]
                ],
            ];

            // Log the request details
            Log::info('Continuing payment with new transaction. Payload:', [
                'transaction_id' => $newTransaction->id,
                'callback_url' => $callbackUrl,
                'amount' => $amount
            ]);

            // Make API request to Flutterwave
            $response = Http::timeout(40)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/payments', $payload);

            $responseData = $response->json();

            // Log the response
            Log::info('Flutterwave payment response:', $responseData);

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === 'success') {
                // Update transaction with the new payment link
                $newTransaction->payment_link = $responseData['data']['link'];
                $newTransaction->save();

                Log::info("Generated new payment link for transaction {$newTransaction->id}: {$responseData['data']['link']}");

                return redirect()->away($responseData['data']['link']);
            }

            // If we get here, something went wrong with the Flutterwave API call
            Log::error('Failed to generate payment link with Flutterwave:', $responseData);

            return redirect()->back()->with('error', $responseData['message'] ?? 'Failed to generate payment link. Please try again.');
        } catch (\Exception $e) {
            Log::error('Exception while continuing payment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'An error occurred while processing your payment. Please try again later.');
        }
    }

    public function handleCallback(Request $request)
    {
        Log::info('Flutterwave Callback Received:', $request->all());

        $status = $request->input('status');
        $txRef = $request->input('tx_ref');
        $transactionId = $request->input('transaction_id');

        if (!$txRef) {
            Log::error("Flutterwave callback missing tx_ref");
            return redirect()->route('payments.error')->with('error', 'Invalid payment reference');
        }

        // Find the local transaction
        $transaction = Transaction::where('reference', $txRef)->first();

        if (!$transaction) {
            Log::error("Transaction not found for reference: {$txRef}");
            return redirect()->route('payments.error')->with('error', 'Transaction not found');
        }

        // If the transaction is already processed, redirect accordingly
        if ($transaction->status === 'paid') {
            return redirect()->route('payments.success', ['reference' => $txRef]);
        }

        if ($transaction->status === 'failed') {
            return redirect()->route('payments.failed', ['reference' => $txRef]);
        }

        // Verify the transaction with Flutterwave
        try {
            $verification = $this->verifyTransaction($txRef);

            if ($verification['verified']) {
                // Transaction verified as successful
                return redirect()->route('payments.success', ['reference' => $txRef]);
            } else {
                // Payment failed or is pending
                $failureReason = $verification['message'] ?? 'Payment verification failed';
                return redirect()->route('payments.failed', [
                    'reference' => $txRef,
                    'reason' => $failureReason
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception in callback: ' . $e->getMessage());

            return redirect()->route('payments.error', [
                'reference' => $txRef
            ])->with('error', 'An error occurred while processing your payment');
        }
    }

    protected function updateTransactionWithPaymentLink($transaction, $paymentLink)
    {
        Transaction::where('id', $transaction->id)->update([
            'payment_link' => $paymentLink,
        ]);

        // For this example, we'll log instead
        Log::info("Updated transaction {$transaction->id} with payment link: {$paymentLink}");
    }

    protected function updateTransactionStatus($txRef, $status, $data = null)
    {
        $transaction = Transaction::where('reference', $txRef)->first();
        if ($transaction) {
            $transaction->update([
                'status' => $status,
                'payment_data' => json_encode($data),
            ]);

            Log::info("Updated transaction with reference {$txRef} to status: {$status}");
            if ($data) {
                Log::info("Payment data: " . json_encode($data));
                Log::info('Payment Data:', $data);
            }
        } else {
            Log::error("Transaction not found for reference: {$txRef}");
        }
    }


    public function retryPayment($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $user = Auth::user();

        // Ensure this user owns this transaction
        if ($transaction->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Get the plan and installment information
        $plan = $transaction->plan;
        $installmentPlanId = $transaction->installment_plan_id;
        $installmentNumber = $transaction->installment_number;

        // Calculate the correct amount for this payment
        $amount = $transaction->amount;

        // If this is an installment, recalculate the amount from the installment plan
        if ($installmentPlanId) {
            $installmentPlan = InstallmentPlan::findOrFail($installmentPlanId);

            // If this is the last installment, use the exact remaining amount
            if ($installmentNumber == $installmentPlan->total_installments) {
                $amount = $installmentPlan->remaining_amount;
            } else {
                $amount = $installmentPlan->amount_per_installment;
            }
        }

        // Create a new transaction for the retry
        $reference = 'RETRY-' . time() . '-' . $user->id;

        $newTransaction = Transaction::create([
            'reference' => $reference,
            'amount' => $amount,
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->profile->phone ?? '',
            'status' => 'pending',
            'user_id' => $user->id,
            'plan_id' => $transaction->plan_id,
            'installment_plan_id' => $installmentPlanId,
            'installment_number' => $installmentNumber
        ]);

        // Generate Flutterwave payment link
        $paymentLink = $this->generateFlutterwaveLink($newTransaction);

        $newTransaction->payment_link = $paymentLink;
        $newTransaction->save();

        return redirect()->to($paymentLink);
    }

    public function index()
    {
        $payments = Payment::latest()->paginate(10);
        return view('pay.index', compact('payments'));
    }


    protected function generateReference()
    {
        return 'FLW-' . time() . '-' . Str::random(8);
    }


    public function submitForm()
    {
        return view('pay.form');
    }


    /**
     * Process a successful payment
     * 
     * @param Transaction $transaction
     * @return void
     */
    public function processSuccessfulPayment(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $user = $transaction->user;
            $plan = $transaction->plan;

            if (!$plan) {
                Log::error("Transaction ID {$transaction->id} has no associated plan.");
                throw new \Exception("Missing plan for transaction {$transaction->id}");
            }

            // Check if this is an installment payment
            if ($transaction->installment_plan_id) {
                $this->processInstallmentPayment($transaction);
            } else {
                // Regular full payment
                $this->processFullPayment($transaction);
            }
        });
    }

    /**
     * Process installment payment
     */
    private function processInstallmentPayment(Transaction $transaction)
    {
        $installmentPlan = InstallmentPlan::find($transaction->installment_plan_id);
        $user = $transaction->user;
        $plan = $transaction->plan;

        if (!$installmentPlan) {
            Log::error("Installment plan {$transaction->installment_plan_id} not found.");
            throw new \Exception("Installment plan not found");
        }

        $installmentNumber = $transaction->installment_number ?? $installmentPlan->paid_installments + 1;

        // Link this transaction to the installment plan using the pivot table
        $installmentPlan->transactions()->attach($transaction->id, [
            'installment_number' => $installmentNumber,
            'applied_amount' => $transaction->amount
        ]);

        // Update the installment plan stats
        $installmentPlan->updatePaymentStats();

        // If all installments are paid
        if ($installmentPlan->status === 'completed') {
            // Activate the user's subscription
            $isLifeMembership = $plan->membershipCategory->name === 'life' ||
                strtolower($plan->name) === 'life membership';

            DB::table('user_plans')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'plan_id' => $plan->id,
                    'subscribed_at' => now(),
                    'expires_at' => $isLifeMembership ? now()->addYears(20) : now()->addYear(),
                    'updated_at' => now()
                ]
            );

            // Send email for completed subscription
            Mail::to($user->email)->send(new SubscriptionActivatedMail(
                $user,
                $plan,
                $transaction->reference,
                $transaction->payment_method ?? 'Unknown'
            ));

            Log::info("Membership activated for user {$user->id}, plan {$plan->id}");
        } else {
            // Set the next payment date (30 days from now)
            $installmentPlan->next_payment_date = now()->addDays(30);
            $installmentPlan->save();

            // Send email for installment payment
            Mail::to($user->email)->send(new InstallmentPaymentMail(
                $user,
                $plan,
                $transaction->reference,
                $transaction->payment_method ?? 'Unknown',
                $installmentPlan,
                $installmentNumber
            ));

            Log::info("Installment {$installmentNumber} paid for user {$user->id}, plan {$plan->id}. Remaining: {$installmentPlan->remaining_amount}");
        }
    }

    /**
     * Process full payment
     */
    private function processFullPayment(Transaction $transaction)
    {
        $user = $transaction->user;
        $plan = $transaction->plan;

        // Regular full payment
        DB::table('user_plans')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'subscribed_at' => now(),
                'expires_at' => $plan->membershipCategory->name === 'life' ? now()->addYears(20) : now()->addYear(),
                'updated_at' => now()
            ]
        );

        Mail::to($user->email)->send(new SubscriptionActivatedMail(
            $user,
            $plan,
            $transaction->reference,
            $transaction->payment_method ?? 'Unknown'
        ));

        Log::info("Subscription activated for user {$user->id}, plan {$plan->id}");
    }

    public function handleWebhook(Request $request)
    {
        // Validate Flutterwave signature for security
        $signature = $request->header('verif-hash');
        $secretHash = env('FLW_SECRET_HASH');

        if (!$signature || ($secretHash && $signature !== $secretHash)) {
            Log::warning('Invalid webhook signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        Log::info('Flutterwave webhook received:', $request->all());

        try {
            // Process the webhook data
            $event = $request->input('event');
            $data = $request->input('data');

            if ($event === 'charge.completed' && isset($data['tx_ref'])) {
                $txRef = $data['tx_ref'];
                $transaction = Transaction::where('reference', $txRef)->first();

                if ($transaction && $transaction->status !== 'paid') {
                    $status = $data['status'] ?? '';

                    if ($status === 'successful') {
                        // Update transaction as paid
                        $transaction->status = 'paid';
                        $transaction->payment_method = $data['payment_type'] ?? 'Unknown';
                        $transaction->payment_data = json_encode($data);
                        $transaction->save();

                        // Process the successful payment
                        $this->processSuccessfulPayment($transaction);

                        Log::info("Webhook: Transaction {$transaction->id} marked as paid");
                    } elseif (in_array($status, ['failed', 'cancelled'])) {
                        // Mark as failed
                        $transaction->status = 'failed';
                        $transaction->payment_data = json_encode($data);
                        $transaction->save();

                        Log::info("Webhook: Transaction {$transaction->id} marked as failed");
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }
    }
}
