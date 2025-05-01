<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Plan;

class SubscriptionActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $confirmationCode;
    public $paymentMethod;
    public $subscriptionStart;
    public $subscriptionExpiry;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Plan $plan, $confirmationCode, $paymentMethod)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->confirmationCode = $confirmationCode;
        $this->paymentMethod = $paymentMethod;
        $this->subscriptionStart = now();
        $this->subscriptionExpiry = now()->addYear();
    }

    public function build()
    {
        return $this->subject('Subscription Activated')
                    ->view('emails.subscription_activated')
                    ->with([
                        'user' => $this->user,
                        'plan' => $this->plan,
                        'confirmationCode' => $this->confirmationCode,
                        'paymentMethod' => $this->paymentMethod,
                        'subscriptionStart' => $this->subscriptionStart->format('F d, Y'),
                        'subscriptionExpiry' => $this->subscriptionExpiry->format('F d, Y'),
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Activated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_activated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}