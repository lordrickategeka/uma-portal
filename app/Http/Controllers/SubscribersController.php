<?php

namespace App\Http\Controllers;

use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SubscribersController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::paginate(10);
        return view('dashboard.newsSubscribers.index', compact('subscribers'));
    }

    public function subscribe(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:subscribers,email',
        ]);

        // Generate subscription token
        $token = Subscriber::generateToken();

        // Create the subscriber
        $subscriber = Subscriber::create([
            'email' => $validated['email'],
            'subscription_token' => $token,
            'is_active' => true,
        ]);

        try {
            // Send confirmation email
            Mail::to($subscriber->email)
                ->send(new SubscriberConfirmation($subscriber));
        } catch (\Exception $e) {
            Log::error('Failed to send subscription confirmation email: ' . $e->getMessage());
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Thank you for subscribing! A confirmation email has been sent to your inbox.');
    }

    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('subscription_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Invalid unsubscribe link.');
        }

        $subscriber->update([
            'is_active' => false,
        ]);

        return view('dashboard.newsSubscribers.unsubscribed');
    }

    public function destroy(Subscriber $subscriber)
    {
        try {
            $subscriber->delete();

            return redirect()->route('subscribers.index')
                ->with('success', 'Subscriber deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('subscribers.index')
                ->with('error', 'Error deleting subscriber: ' . $e->getMessage());
        }
    }
}
