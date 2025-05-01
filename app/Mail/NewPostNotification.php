<?php

namespace App\Mail;

use App\Models\Subscriber;
use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewPostNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The subscriber instance.
     *
     * @var \App\Models\Subscriber
     */
    public $subscriber;

    /**
     * The blog post instance.
     *
     * @var \App\Models\Blog
     */
    public $blog;

    /**
     * The excerpt of the blog content.
     *
     * @var string
     */
    public $excerpt;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @param  \App\Models\Blog  $blog
     * @return void
     */
    public function __construct(Subscriber $subscriber, Blog $blog)
    {
        $this->subscriber = $subscriber;
        $this->blog = $blog;
        $this->excerpt = $blog->getExcerpt(200);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Blog Post: ' . $this->blog->title,
            to: [$this->subscriber->email],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-post-notification',
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