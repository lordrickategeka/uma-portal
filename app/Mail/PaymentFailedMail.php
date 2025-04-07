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

class PaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $failureReason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Plan $plan, $failureReason)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->failureReason = $failureReason;
    }

    public function build()
    {
        return $this->subject('Payment Failed')
                    ->view('emails.payment_failed')
                    ->with([
                        'user' => $this->user,
                        'plan' => $this->plan,
                        'failureReason' => $this->failureReason,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Failed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_failed',
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