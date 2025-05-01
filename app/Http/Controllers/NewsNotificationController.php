<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class NewsNotificationController extends Controller
{
    /**
     * The subscription service instance.
     *
     * @var \App\Services\SubscriptionService
     */
    protected $subscriptionService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\SubscriptionService  $subscriptionService
     * @return void
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->middleware(['auth']);
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display notification dashboard for admins.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get recent posts and events that haven't had notifications sent yet
        $pendingPosts = Blog::where('post_type', 'post')
                          ->where('status', 'published')
                          ->whereNotNull('published_at')
                          ->where('published_at', '>=', now()->subWeeks(2))
                          ->where('published_at', '<=', now())
                          ->where('notification_sent', false)
                          ->get();
                          
        $pendingEvents = Blog::where('post_type', 'event')
                           ->where('status', 'published')
                           ->whereNotNull('published_at')
                           ->where('published_at', '>=', now()->subWeeks(2))
                           ->where('published_at', '<=', now())
                           ->where('notification_sent', false)
                           ->with('event')
                           ->get();
        
        // Get counts of recently notified posts and events
        $notifiedPostsCount = Blog::where('post_type', 'post')
                                 ->where('notification_sent', true)
                                 ->where('published_at', '>=', now()->subWeeks(2))
                                 ->count();
        
        $notifiedEventsCount = Blog::where('post_type', 'event')
                                  ->where('notification_sent', true)
                                  ->where('published_at', '>=', now()->subWeeks(2))
                                  ->count();
        
        return view('dashboard.notifications.index', [
            'pendingPosts' => $pendingPosts,
            'pendingEvents' => $pendingEvents,
            'notifiedPostsCount' => $notifiedPostsCount,
            'notifiedEventsCount' => $notifiedEventsCount,
        ]);
    }

    /**
     * Send notification for a specific post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $blogId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPostNotification(Request $request, $blogId)
    {
        $blog = Blog::findOrFail($blogId);
        
        // Check if the blog is a post, is published and recent
        if ($blog->post_type !== 'post' || $blog->status !== 'published' || $blog->published_at->diffInWeeks(now()) > 2) {
            return redirect()->route('notifications.index')
                             ->with('error', 'Notification can only be sent for published posts within the last 2 weeks.');
        }
        
        // Send notifications
        $this->subscriptionService->notifySubscribers($blog, 'post');
        
        // Mark as notified
        $blog->update(['notification_sent' => true]);
        
        return redirect()->route('notifications.index')
                         ->with('success', "Notification for post '{$blog->title}' has been sent to all subscribers.");
    }

    /**
     * Send notification for a specific event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $blogId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEventNotification(Request $request, $blogId)
    {
        $blog = Blog::with('event')->findOrFail($blogId);
        
        // Check if the blog is an event, is published and recent
        if ($blog->post_type !== 'event' || $blog->status !== 'published' || $blog->published_at->diffInWeeks(now()) > 2) {
            return redirect()->route('notifications.index')
                             ->with('error', 'Notification can only be sent for published events within the last 2 weeks.');
        }
        
        // Send notifications
        $this->subscriptionService->notifySubscribers($blog, 'event');
        
        // Mark as notified
        $blog->update(['notification_sent' => true]);
        
        return redirect()->route('notifications.index')
                         ->with('success', "Notification for event '{$blog->title}' has been sent to all subscribers.");
    }

    /**
     * Send notifications for all pending content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendAllNotifications(Request $request)
    {
        // Get pending posts
        $pendingPosts = Blog::where('post_type', 'post')
                          ->where('status', 'published')
                          ->whereNotNull('published_at')
                          ->where('published_at', '>=', now()->subWeeks(2))
                          ->where('published_at', '<=', now())
                          ->where('notification_sent', false)
                          ->get();
        
        // Get pending events
        $pendingEvents = Blog::where('post_type', 'event')
                           ->where('status', 'published')
                           ->whereNotNull('published_at')
                           ->where('published_at', '>=', now()->subWeeks(2))
                           ->where('published_at', '<=', now())
                           ->where('notification_sent', false)
                           ->with('event')
                           ->get();
        
        $postCount = 0;
        $eventCount = 0;
        
        // Process posts
        foreach ($pendingPosts as $post) {
            $this->subscriptionService->notifySubscribers($post, 'post');
            $post->update(['notification_sent' => true]);
            $postCount++;
        }
        
        // Process events
        foreach ($pendingEvents as $event) {
            $this->subscriptionService->notifySubscribers($event, 'event');
            $event->update(['notification_sent' => true]);
            $eventCount++;
        }
        
        return redirect()->route('notifications.index')
                         ->with('success', "Notifications sent for $postCount posts and $eventCount events.");
    }
}