<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlutterPaymentController extends Controller
{

    // public function __construct()
    // {
    //     $this->baseUrl = config('flutterwave.base_url', 'https://api.flutterwave.com/v3');
    //     $this->secretKey = config('flutterwave.secret_key');
    //     $this->publicKey = config('flutterwave.public_key');
    //     $this->encryptionKey = config('flutterwave.encryption_key');
    //     $this->callbackUrl = config('flutterwave.callback_url');
    // }

    protected $baseUrl = 'https://api.flutterwave.com/v3';
    protected $secretKey = 'FLWSECK_TEST-6fbbbf3348c1d2a3f1ed1a181f1feb6e-X';
    protected $publicKey = 'FLWPUBK_TEST-ab98cd282939f74fd92979f9aff952e9-X';
    protected $encryptionKey = 'FLWSECK_TEST36e9172d89b2';
    protected $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = route('flutterwave.callback');
    }

    // public function initializePayment(Request $request)
    // {
    //     // Validate request
    //     $validated = $request->validate([
    //         'amount' => 'required|numeric',
    //         'email' => 'required|email',
    //         'name' => 'required|string',
    //         'phone' => 'required|string',
    //         'reference' => 'nullable|string',
    //     ]);

    //     // Generate a unique transaction reference if not provided
    //     $reference = $validated['reference'] ?? $this->generateReference();

    //     // Save transaction to database
    //     $transaction = $this->saveTransaction($validated, $reference);

    //     // Prepare payment payload
    //     $payload = [
    //         'tx_ref' => $reference,
    //         'amount' => $validated['amount'],
    //         'currency' => 'UGX', // Change according to your currency
    //         'payment_options' => 'card, banktransfer, ussd, mobile_money',
    //         'redirect_url' => route('flutterwave.callback'),
    //         'customer' => [
    //             'email' => $validated['email'],
    //             'name' => $validated['name'],
    //             'phonenumber' => $validated['phone'],
    //         ],
    //         'meta' => [
    //             'transaction_id' => $transaction->id,
    //             'user_id' => Auth::user()->id ?? null,
    //         ],
    //         'customizations' => [
    //             'title' => config('app.name') . ' Payment',
    //             'description' => 'Payment for products/services',
    //             'logo' => config('flutterwave.logo_url', ''),
    //         ],
    //     ];

    //     // Make API request to Flutterwave
    //     try {
    //         $response = Http::withoutVerifying()
    //             ->withHeaders([
    //                 'Authorization' => 'Bearer ' . $this->secretKey,
    //                 'Content-Type' => 'application/json',
    //             ])->post($this->baseUrl . '/payments', $payload);

    //         $responseData = $response->json();

    //         Log::info('Flutterwave payload:', $payload);
    //         Log::info('Flutterwave response:', $responseData);

    //         if ($response->successful() && isset($responseData['status']) && $responseData['status'] === 'success') {
    //             // Update transaction with payment link
    //             $this->updateTransactionWithPaymentLink($transaction, $responseData['data']['link']);

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Payment link generated successfully',
    //                 'data' => [
    //                     'payment_link' => $responseData['data']['link'],
    //                     'reference' => $reference,
    //                 ],
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $responseData['message'] ?? 'Failed to initialize payment',
    //         ], 400);
    //     } catch (\Exception $e) {
    //         Log::error('Flutterwave payment initialization error: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your payment',
    //         ], 500);
    //     }
    // }

    public function initializePayment(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'amount' => 'required|numeric',
        'email' => 'required|email',
        'name' => 'required|string',
        'phone' => 'required|string',
        'reference' => 'nullable|string',
    ]);

    // Generate a unique transaction reference if not provided
    $reference = $validated['reference'] ?? $this->generateReference();

    // Save transaction to database
    $transaction = $this->saveTransaction($validated, $reference);

    if (!$transaction) {
        return response()->json([
            'status' => 'error',
            'message' => 'Could not save transaction',
        ], 500);
    }

    // Prepare payment payload
    $payload = [
        'tx_ref' => $reference,
        'amount' => $validated['amount'],
        'currency' => 'UGX',
        'payment_options' => 'card, banktransfer, ussd, mobile_money',
        'redirect_url' => 'https://09ea-197-239-14-229.ngrok-free.app/payments/callback',
        'customer' => [
            'email' => $validated['email'],
            'name' => $validated['name'],
            'phone_number' => $validated['phone'],
        ],
        'meta' => [
            'transaction_id' => $transaction->id,
            'user_id' => Auth::user()->id ?? null,
        ],
        'customizations' => [
            'title' => config('app.name') . ' Payment',
            'description' => 'Payment for products/services',
            'logo' => config('flutterwave.logo_url', ''),
        ],
    ];

    // Make API request to Flutterwave
    try {
        // Log the payload for debugging
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
        $responseData = $response->json();
        Log::info('Flutterwave payment response: ' . json_encode($responseData));

        if ($response->successful() && isset($responseData['status']) && $responseData['status'] === 'success') {
            // Update transaction with payment link
            $this->updateTransactionWithPaymentLink($transaction, $responseData['data']['link']);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment link generated successfully',
                'data' => [
                    'payment_link' => $responseData['data']['link'],
                    'reference' => $reference,
                ],
            ]);
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



    public function handleCallback(Request $request)
    {
        $status = $request->input('status');
        $txRef = $request->input('tx_ref');
        $transactionId = $request->input('transaction_id');

        // Verify the transaction
        try {
            $verificationResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/transactions/' . $transactionId . '/verify');

            $responseData = $verificationResponse->json();

            if (
                $verificationResponse->successful() &&
                isset($responseData['status']) &&
                $responseData['status'] === 'success' &&
                isset($responseData['data']['status']) &&
                $responseData['data']['status'] === 'successful'
            ) {

                // Update transaction status
                $this->updateTransactionStatus($txRef, 'completed', $responseData['data']);

                // Redirect to success page
                return redirect()->route('payments.success', [
                    'reference' => $txRef
                ]);
            } else {
                // Payment failed or is pending
                $this->updateTransactionStatus($txRef, $responseData['data']['status'] ?? 'failed', $responseData['data'] ?? null);

                // Redirect to failed page
                return redirect()->route('payments.failed', [
                    'reference' => $txRef
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave callback verification error: ' . $e->getMessage());

            // Update transaction status as error
            $this->updateTransactionStatus($txRef, 'error', null);

            // Redirect to error page
            return redirect()->route('payments.error', [
                'reference' => $txRef
            ]);
        }
    }

    public function verifyTransaction($reference)
    {
        try {
            $response = Http::withHeaders([
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

                // Update transaction status
                $this->updateTransactionStatus($reference, 'completed', $responseData['data']);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment verified successfully',
                    'data' => $responseData['data'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed',
                'data' => $responseData['data'] ?? null,
            ], 400);
        } catch (\Exception $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while verifying your payment',
            ], 500);
        }
    }

    protected function generateReference()
    {
        return 'FLW-' . time() . '-' . Str::random(8);
    }


    protected function saveTransaction($data, $reference)
    {
        // This is a placeholder method
        // You should implement this according to your database structure

        // Example implementation:
        return Transaction::create([
            'reference' => $reference,
            'amount' => $data['amount'],
            'email' => $data['email'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'status' => 'pending',
            'user_id' => Auth::user()->id ?? null,
        ]);

        // For this example, we'll return a simple object
        // return (object) [
        //     'id' => rand(1000, 9999),
        //     'reference' => $reference,
        //     'amount' => $data['amount'],
        //     'email' => $data['email'],
        //     'name' => $data['name'],
        //     'phone' => $data['phone'],
        //     'status' => 'pending',
        //     'user_id' => Auth::user()->id ?? null,
        // ];
    }

    protected function updateTransactionWithPaymentLink($transaction, $paymentLink)
    {
        // This is a placeholder method
        // You should implement this according to your database structure

        // Example implementation:
        // Transaction::where('id', $transaction->id)->update([
        //     'payment_link' => $paymentLink,
        // ]);

        // For this example, we'll log instead
        Log::info("Updated transaction {$transaction->id} with payment link: {$paymentLink}");
    }

    protected function updateTransactionStatus($reference, $status, $data = null)
    {
        // This is a placeholder method
        // You should implement this according to your database structure

        // Example implementation:
        // $transaction = Transaction::where('reference', $reference)->first();
        // if ($transaction) {
        //     $transaction->update([
        //         'status' => $status,
        //         'payment_data' => json_encode($data),
        //     ]);
        // }

        // For this example, we'll log instead
        Log::info("Updated transaction with reference {$reference} to status: {$status}");
        if ($data) {
            Log::info("Payment data: " . json_encode($data));
        }
    }
    public function index()
    {
        $payments = Payment::latest()->paginate(10);
        return view('pay.index', compact('payments'));
    }



    public function submitForm()
    {
        return view('pay.form');
    }
}
