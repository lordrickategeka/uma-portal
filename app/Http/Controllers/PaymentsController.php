<?php

namespace App\Http\Controllers;

use App\Mail\PaymentFailedMail;
use App\Mail\SubscriptionActivatedMail;
use App\Models\Order;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller
{
    public function showPaymentPage(Plan $plan)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->first();
        return view('payment.payment_page', compact('plan', 'order'));
    }

    private $pesapalBaseUrl;
    private $authUrl;
    private $transactionStatusUrl;
    private $submitOrderUrl;

    public function __construct()
    {
        // Set up URLs based on environment
        $isProduction = config('pesapal.environment') === 'production';
        $this->pesapalBaseUrl = $isProduction
            ? 'https://www.pesapal.com/pesapalv3/api'
            : 'https://cybqa.pesapal.com/pesapalv3/api';

        $this->authUrl = $this->pesapalBaseUrl . '/Auth/RequestToken';
        $this->submitOrderUrl = $this->pesapalBaseUrl . '/Transactions/SubmitOrderRequest';
        $this->transactionStatusUrl = $this->pesapalBaseUrl . '/Transactions/GetTransactionStatus';
    }


    private function getPesapalToken()
    {
        try {
            Log::info('Attempting to get Pesapal Token', [
                'auth_url' => $this->authUrl,
                'consumer_key' => config('pesapal.consumer_key') ? 'SET' : 'NOT SET'
            ]);

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->post($this->authUrl, [
                    'consumer_key' => config('pesapal.consumer_key'),
                    'consumer_secret' => config('pesapal.consumer_secret')
                ]);

            Log::info('Pesapal Token Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $responseData = $response->json();

            if (isset($responseData['token'])) {
                return $responseData['token']; // Ensure the token is correctly returned
            }

            Log::error('Failed to retrieve token from Pesapal response', ['response' => $responseData]);
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Auth Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function subscriptionPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to subscribe.');
        }

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Check if the user has an existing pending order
        // $existingOrder = Order::where('user_id', $user->id)
        //     ->where('plan_id', $plan->id)
        //     ->where('payment_status', 'pending')
        //     ->first();

        // if ($existingOrder) {
        //     $order = $existingOrder;
        //     Log::info("Existing pending order found: " . $order->id);
        // } else {
        $totalAmount = $plan->price + 2579;  // Including service fee
        $merchantReference = "SUB-" . time() . "-" . rand(1000, 9999);

        // Fetch default payment method
        $userPaymentMethod = $user->UserPaymentMethods()->where('is_default', true)->first();

        if (!$userPaymentMethod || !$userPaymentMethod->paymentMethod) {
            return redirect()->back()->with('error', 'No valid payment method found.');
        }

        // Create a new order
        $order = Order::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'reference_number' =>  $merchantReference,
            'payment_method_id' => $userPaymentMethod->paymentMethod->id
        ]);
        Log::info("New order created: " . $order->id);
        // }

        // try {
        // Get Pesapal token and handle payment initiation
        $token = $this->getPesapalToken();
        if (!$token) {
            return redirect()->route('payment.failed', ['order' => $order->id])
                ->with('error', 'Failed to authenticate with payment gateway');
        }

        // Prepare payment data
        $paymentData = [
            "id" => $merchantReference,
            "currency" => "UGX",
            "amount" => $totalAmount,
            "description" => "Subscription for " . $plan->name,
            "callback_url" => route('payment.callback'),
            "redirect_mode" => "TOP_WINDOW",
            "notification_id" => config('pesapal.notification_id', 'fa0578c5-d76f-4f81-bdb6-dc153b76cef7'),
            "cancellation_url" => route('subscriptions.index'),
            "branch" => "UMA - HQ",
            "billing_address" => [
                "email_address" => $user->email,
                "phone_number" => $user->profile->phone,
                "country_code" => "UG", // Use appropriate country code
                "first_name" => $user->first_name,
                "middle_name" => "",
                "last_name" => $user->last_name ?? '',
                "line_1" => 'kampala,256' ?? '',
                "line_2" => "",
                "city" => 'kampala' ?? '',
                "state" => "",
                "postal_code" => "",
                "zip_code" => "",
            ]
        ];

        // Add split payment details if needed
        if (config('pesapal.use_split_payment', false)) {
            $serviceFee = config('pesapal.service_fee', 100);
            $serviceAccount = config('pesapal.service_account_id');
            $vendorAccount = config('pesapal.vendor_account_id');

            $paymentData['split_details'] = [
                [
                    "account_number" => $serviceAccount,
                    "amount" => $serviceFee,
                    "type" => "FIXED"
                ],
                [
                    "account_number" => $vendorAccount,
                    "amount" => $totalAmount - $serviceFee,
                    "type" => "BALANCE"
                ]
            ];
        }

        // Send payment initiation request to Pesapal
        $response = Http::withoutVerifying()
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->submitOrderUrl, $paymentData);

        if ($response->successful()) {
            $responseData = $response->json();

            // Store tracking ID and payment status in order
            $order->update([
                'payment_tracking_id' => $responseData['order_tracking_id'],
                'payment_reference' => $responseData['merchant_reference'],
                'confirmation_code' => $responseData['confirmation_code'] ?? null,
                // 'payment_status' => $responseData['payment_status_description'] === 'Completed' ? 'paid' : 'failed' ?? null,
                'payment_status_code' => $responseData['payment_status_code'] ?? null,
                'payment_account' => $responseData['payment_account'] ?? null,
                'call_back_url' => $responseData['call_back_url'] ?? null,
            ]);

            Log::info("Payment initiated: " . $responseData['order_tracking_id']);

            return view('payment.iframe', [
                'paymentUrl' => $responseData['redirect_url'],
                'order' => $order
            ]);
        } else {
            Log::error('Pesapal Error: ' . json_encode($response->json()));
            return redirect()->route('payment.failed', ['order' => $order->id])
                ->with('error', 'Payment initiation failed: ' . ($response->json()['message'] ?? 'Unknown error'));
        }
        // } catch (\Exception $e) {
        //     Log::error('Pesapal Payment Error: ' . $e->getMessage());
        //     return redirect()->route('payment.failed', ['order' => $order->id])
        //         ->with('error', 'Something went wrong with the payment');
        // }
    }

    public function verifyPesapalPayment($trackingId)
    {
        $token = $this->getPesapalToken();
        if (!$token) {
            Log::error('Pesapal Authentication Failed: Unable to get access token');
            return null;
        }

        $apiUrl = $this->transactionStatusUrl . "?orderTrackingId={$trackingId}";
        // $apiUrl = "{$this->transactionStatusUrl}/{$trackingId}";

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->get($apiUrl);

            if ($response->successful()) {
                Log::info('Pesapal Payment Verification Success', ['tracking_id' => $trackingId, 'response' => $response->json()]);
                return $response->json();
            } else {
                Log::error('Pesapal API Error', [
                    'tracking_id' => $trackingId,
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Pesapal API Request Failed', [
                'tracking_id' => $trackingId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function processPayment($order, $statusResponse)
    {
        $paymentStatus = $statusResponse['payment_status_description'];
        $statusCode = $statusResponse['status_code'] ?? 0;
        $confirmationCode = $statusResponse['confirmation_code'] ?? null;
        $paymentMethod = $statusResponse['payment_method'] ?? 'Unknown';

        Log::info("Processing payment for order {$order->id}, status: {$paymentStatus}, code: {$statusCode}");

        $failureReason = match(true) {
            $statusCode == 0 => 'Payment initialization failed',
            $statusCode == 2 => 'Payment was declined',
            $statusCode == 3 => 'Payment timed out',
            $statusCode == 4 => 'Insufficient funds',
            default => $paymentStatus ?: 'Unknown payment failure'
        };

        if ($statusCode == 1) { // COMPLETED
            DB::transaction(function () use ($order, $confirmationCode, $paymentMethod) {
                $order->update([
                    'payment_status' => 'paid',
                    'confirmation_code' => $confirmationCode,
                    'payment_method' => $paymentMethod,
                    'payment_date' => now(),
                ]);

                // Ensure plan_id exists before proceeding
                if (!$order->plan_id) {
                    Log::error("Order ID {$order->id} has a NULL plan_id");
                    throw new \Exception("Missing plan information for order {$order->id}");
                }

                // Activate subscription
                DB::table('user_plans')->updateOrInsert(
                    ['user_id' => $order->user_id, 'plan_id' => $order->plan_id],
                    [
                        'subscribed_at' => now(), // Updated to match the column name
                        'expires_at' => now()->addYear(),
                        'updated_at' => now()
                    ]
                );

                Mail::to($order->user->email)->send(new SubscriptionActivatedMail(
                    $order->user, 
                    $order->plan, 
                    $confirmationCode, 
                    $paymentMethod
                ));

                Log::info("Subscription activated for user {$order->user_id}, plan {$order->plan_id}");
            });

            return true;
        } else {
            Mail::to($order->user->email)->send(new PaymentFailedMail(
                $order->user, 
                $order->plan, 
                $paymentStatus,
                $failureReason
            ));
    
            // Mark order as failed if not completed
            $order->update(['payment_status' => 'failed']);
            Log::warning("Payment failed for order {$order->id}. Status: {$paymentStatus}, Code: {$statusCode}");
            
            return false;
        }
    }


    public function handleCallback(Request $request)
    {
        $trackingId = $request->input('OrderTrackingId');

        if (!$trackingId) {
            Log::error("Pesapal Callback: Missing OrderTrackingId");
            return redirect()->route('payment.failed')->with('error', 'Invalid request.');
        }

        // Retrieve the order
        $order = Order::where('payment_tracking_id', $trackingId)->first();
        if (!$order) {
            Log::error("Pesapal Callback: No order found for Tracking ID: {$trackingId}");
            return view('payment.redirect_to_payment')->with('error', 'No order specified');
        }

        // Fetch payment status
        $statusResponse = $this->verifyPesapalPayment($trackingId);

        if (!$statusResponse || !isset($statusResponse['payment_status_description'])) {
            Log::error("Pesapal Callback: Invalid response for Tracking ID: {$trackingId}", ['response' => $statusResponse]);
            return redirect()->route('payment.failed', ['order' => $order->id])->with('error', 'Could not verify payment status.');
        }

        try {
            $success = $this->processPayment($order, $statusResponse);

            if ($success) {
                return redirect()->route('payment.success', ['order' => $order->id])
                    ->with('success', 'Payment successful! Subscription activated.');
            } else {
                $paymentStatus = $statusResponse['payment_status_description'];
                return redirect()->route('payment.failed', ['order' => $order->id])
                    ->with('error', "Payment failed or pending verification. Status: {$paymentStatus}");
            }
        } catch (\Exception $e) {
            Log::error("Error processing payment: " . $e->getMessage());
            return redirect()->route('payment.failed', ['order' => $order->id])
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }


    public function handleIPN(Request $request)
    {
        $trackingId = $request->input('OrderTrackingId');
        $merchantReference = $request->input('OrderMerchantReference');

        if (!$trackingId || !$merchantReference) {
            Log::error("Pesapal IPN: Missing required parameters");
            return response()->json(['status' => 500]);
        }

        $order = Order::where('payment_tracking_id', $trackingId)->first();
        if (!$order) {
            Log::error("Pesapal IPN: No order found for Tracking ID: {$trackingId}");
            return response()->json(['status' => 500]);
        }

        $statusResponse = $this->verifyPesapalPayment($trackingId);
        if (!$statusResponse || !isset($statusResponse['payment_status_description'])) {
            Log::error("Pesapal IPN: Invalid response for Tracking ID: {$trackingId}", ['response' => $statusResponse]);
            return response()->json(['status' => 500]);
        }

        try {
            // Process the payment - IPN handling can be async, so we don't need to return status to user
            $this->processPayment($order, $statusResponse);
        } catch (\Exception $e) {
            Log::error("Error processing IPN: " . $e->getMessage());
        }

        // Always acknowledge receipt of IPN
        Log::info("Pesapal IPN Processed for Tracking ID: {$trackingId}");
        return response()->json([
            "orderNotificationType" => "IPNCHANGE",
            "orderTrackingId" => $trackingId,
            "orderMerchantReference" => $merchantReference,
            "status" => 200
        ]);
    }

    // Payment success view
    public function paymentSuccess(Order $order)
    {
        $order = Order::findOrFail($order->id);
        $user = Auth::user();

        $userPlan = DB::table('user_plans')->where('user_id', $user->id)->first();

        if ($userPlan) {
            $plan = DB::table('plans')
                ->where('id', $userPlan->plan_id)
                ->first();

            if ($plan) {
                // Pass the plan to the view
                return view('payment.success', compact('order', 'plan'));
            }
        }
    }

    // Payment failed view
    public function paymentFailed(Request $request)
    {
        $orderId = $request->input('order');
        if (!$orderId) {
            // Handle case where no order is provided
            return view('payment.redirect_to_payment')->with('error', 'No order specified');
        }

        $order = Order::findOrFail($orderId);
        return view('payment.failed', compact('order'));
    }

    // Handle canceled payment (optional route)
    public function cancelPayment()
    {
        return view('payment.cancel'); // Show cancel payment page
    }
}
