<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class NewContentNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $content;
    public $contentType;


    public function __construct(Subscriber $subscriber, Model $content, string $contentType)
    {
        $this->subscriber = $subscriber;
        $this->content = $content;
        $this->contentType = $contentType;
    }

    public function envelope(): Envelope
    {
        $subject = 'New ' . ucfirst($this->contentType) . ': ' . $this->content->title;
        
        return new Envelope(
            subject: $subject,
            to: [$this->subscriber->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-content-notification',
        );
    }

   
    public function attachments(): array
    {
        return [];
    }
}
