<?php

namespace App\Mail;

use App\Models\ContactFormEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $contactEntry;

    public function __construct(ContactFormEntry $contactEntry)
    {
        $this->contactEntry = $contactEntry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission',
            to: [config('mail.admin_address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
