<?php

namespace App\Services;

use App\Mail\NewPostNotification;
use App\Mail\NewEventNotification;
use App\Models\Subscriber;
use App\Models\Blog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Send notifications to all active subscribers about new content.
     *
     * @param  \App\Models\Blog  $blog
     * @param  string  $contentType
     * @return int Number of notifications sent
     */
    public function notifySubscribers(Blog $blog, string $contentType)
    {
        // Validate the content is actually published and recent
        if ($blog->status !== 'published' || $blog->published_at->diffInWeeks(now()) > 2) {
            Log::warning("Content '{$blog->title}' is not eligible for notification: status={$blog->status}, published_at={$blog->published_at}");
            return 0;
        }

        // For events, make sure the event data is loaded
        if ($contentType === 'event' && !$blog->relationLoaded('event')) {
            $blog->load('event');
        }

        // Get all active subscribers
        $subscribers = Subscriber::active()->get();
        $sentCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                if ($contentType === 'post') {
                    Mail::to($subscriber->email)
                        ->queue(new NewPostNotification($subscriber, $blog));
                } elseif ($contentType === 'event') {
                    Mail::to($subscriber->email)
                        ->queue(new NewEventNotification($subscriber, $blog));
                }
                $sentCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send {$contentType} notification to {$subscriber->email}: " . $e->getMessage());
            }
        }

        Log::info("Sent {$contentType} notifications to {$sentCount} subscribers for '{$blog->title}'");
        return $sentCount;
    }

    /**
     * Check for any recently published content that hasn't had notifications sent yet
     * and send notifications for them.
     *
     * @return array Counts of notifications sent
     */
    public function sendPendingNotifications()
    {
        $result = [
            'posts' => 0,
            'events' => 0
        ];

        // Get pending posts
        $pendingPosts = Blog::where('post_type', 'post')
                          ->where('status', 'published')
                          ->whereNotNull('published_at')
                          ->where('published_at', '>=', now()->subWeeks(2))
                          ->where('published_at', '<=', now())
                          ->where('notification_sent', false)
                          ->get();
                          
        foreach ($pendingPosts as $post) {
            $sentCount = $this->notifySubscribers($post, 'post');
            if ($sentCount > 0) {
                $post->update(['notification_sent' => true]);
                $result['posts']++;
            }
        }

        // Get pending events
        $pendingEvents = Blog::where('post_type', 'event')
                           ->where('status', 'published')
                           ->whereNotNull('published_at')
                           ->where('published_at', '>=', now()->subWeeks(2))
                           ->where('published_at', '<=', now())
                           ->where('notification_sent', false)
                           ->with('event')
                           ->get();
                           
        foreach ($pendingEvents as $event) {
            $sentCount = $this->notifySubscribers($event, 'event');
            if ($sentCount > 0) {
                $event->update(['notification_sent' => true]);
                $result['events']++;
            }
        }

        return $result;
    }
}