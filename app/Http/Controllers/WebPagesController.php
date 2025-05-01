<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormConfirmation;
use App\Mail\ContactFormSubmitted;
use App\Models\Blog;
use App\Models\ContactFormEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebPagesController extends Controller
{
    public function index() {
        $blogs = Blog::with(['author', 'categories', 'tags', 'branch']) 
            ->where('status', 'published')
            ->where('post_type', 'post')
            ->latest() 
            ->paginate(6);  
        return view('web.pages.front_page', compact('blogs'));
    }

    public function contactPage() {
        return view('web.pages.contact_us');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Store the contact form entry in the database
        $contactEntry = ContactFormEntry::create($validated);

        try {
            // Send confirmation email to the user with explicit recipient
            Mail::to($request->email)
                ->send(new ContactFormConfirmation($contactEntry));

            // Send notification email to admin with explicit recipient
            $adminEmail = config('mail.admin_address');
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)
                    ->send(new ContactFormSubmitted($contactEntry));
            } else {
                \Log::error('Admin email address not configured for contact form notifications');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send contact form emails: ' . $e->getMessage());
            // We still want to save the contact and show success even if email fails
        }

        // Return with success message
        return redirect()->back()->with('success', 'Thank you for your message! We will be in touch shortly.');
    }
}
