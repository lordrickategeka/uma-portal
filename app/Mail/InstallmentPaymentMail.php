<?php

namespace App\Mail;

use App\Models\InstallmentPlan;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $reference;
    public $paymentMethod;
    public $installmentPlan;
    public $installmentNumber;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $user, 
        Plan $plan, 
        $reference, 
        $paymentMethod, 
        InstallmentPlan $installmentPlan, 
        $installmentNumber
    ) {
        $this->user = $user;
        $this->plan = $plan;
        $this->reference = $reference;
        $this->paymentMethod = $paymentMethod;
        $this->installmentPlan = $installmentPlan;
        $this->installmentNumber = $installmentNumber;
    }

    public function build()
    {
        return $this->subject('Installment Payment Confirmation')
                    ->markdown('emails.installment-payment');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Installment Payment Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.installment-payment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
