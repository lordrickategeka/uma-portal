<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Subaccount;


class SubaccountController extends Controller
{
    protected $baseUrl = 'https://api.flutterwave.com/v3';
    protected $secretKey = 'FLWSECK_TEST-6fbbbf3348c1d2a3f1ed1a181f1feb6e-X';
    protected $publicKey = 'FLWPUBK_TEST-ab98cd282939f74fd92979f9aff952e9-X';
    protected $encryptionKey = 'FLWSECK_TEST36e9172d89b2';
    protected $callbackUrl;

    public function store(Request $request)
    {

        // Validate incoming request data
        $validated = $request->validate([
            'business_name' => 'required|string',
            'business_email' => 'required|email',
            'account_bank' => 'required|string',
            'account_number' => 'required|string',
            'country' => 'required|string|max:2',
            'split_value' => 'required|numeric|min:0',
            'split_type' => 'required|in:percentage,flat',
            'business_mobile' => 'required|string',
        ]);

        // Create a new subaccount and save it to the database
        $subaccount = Subaccount::create([
            'business_name' => $validated['business_name'],
            'business_email' => $validated['business_email'],
            'account_bank' => $validated['account_bank'],
            'account_number' => $validated['account_number'],
            'country' => $validated['country'],
            'split_value' => $validated['split_value'],
            'split_type' => $validated['split_type'],
            'business_mobile' => $validated['business_mobile'],
        ]);
        // dd($test);
        // Send to Flutterwave
        try {
            $response = Http::timeout(40)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])->post("{$this->baseUrl}/subaccounts", [
                    'account_bank'        => $validated['account_bank'],
                    'business_email'      => $validated['business_email'],
                    'account_number'      => $validated['account_number'],
                    'business_name'       => $validated['business_name'],
                    'country'             => $validated['country'],
                    'split_type'          => $validated['split_type'],
                    'split_value'         => $validated['split_value'],
                    'business_mobile'     => $validated['business_mobile'],
                ]);

            $data = $response->json();

            if ($response->successful() && $data['status'] === 'success') {
                $subaccount->update([
                    'flutterwave_id' => $data['data']['id'] ?? null,
                    'flw_response'   => json_encode($data),
                ]);

                return redirect()->route('subaccounts.create')->with('success', 'Subaccount created successfully on Flutterwave.');
            } else {
                Log::error('Flutterwave Error: ' . json_encode($data));
                return redirect()->back()->withErrors(['error' => 'Failed to create subaccount on Flutterwave.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }

    public function create()
    {
        try {
            // Send GET request to Flutterwave API
            $response = Http::timeout(40)
                ->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,  // Your secret key
                    'Content-Type' => 'application/json',
                ])->get('https://api.flutterwave.com/v3/subaccounts');

            // Check if the request was successful
            if ($response->successful()) {
                $subaccounts = $response->json()['data'];  // Get subaccounts from response

                // Log or process the subaccounts data
                Log::info('Fetched Subaccounts:', $subaccounts);

                $subaccountsArray = $response['data'] ?? [];
                $subaccounts = collect($subaccountsArray)->map(function ($item) {
                    return (object) $item;
                });

                // You can pass the subaccounts data to the view or return it as a response
                return view('dashboard.subaccounts.create', compact('subaccounts'));  // Example view

            } else {
                Log::error('Error fetching subaccounts: ' . $response->body());
                return response()->json(['error' => 'Failed to fetch subaccounts'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching subaccounts: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching subaccounts'], 500);
        }
    }



public function update(Request $request, $id)
{
    $request->validate([
        'business_name' => 'required|string|max:255',
        'split_value' => 'required|numeric',
        'split_type' => 'required|in:flat,percentage',
    ]);

    $payload = [
        'business_name' => $request->business_name,
        'split_value' => $request->split_value,
        'split_type' => $request->split_type,
    ];

    $response = Http::timeout(40)
    ->withoutVerifying()
    ->withToken($this->secretKey)
        ->put("https://api.flutterwave.com/v3/subaccounts/{$id}", $payload);

    $data = $response->json();

    if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
        return redirect()->back()->with('success', 'Subaccount updated successfully.');
    }

    return redirect()->back()->withErrors(['error' => $data['message'] ?? 'Update failed']);
}

}
