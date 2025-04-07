<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class welcomeNewMemberEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to Our Service')
                    ->view('emails.welcomeNewMemeber')  // Ensure this view file exists
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome New Member Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Remove this method or make sure it doesn't reference 'view.name'
        return new Content(
            view: 'emails.welcomeNewMemeber',  // If needed, define it here as well
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
