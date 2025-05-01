<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use App\Models\Blog;
use Illuminate\Console\Command;

class SendPendingNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for any pending posts and events that are published but not yet notified';

    /**
     * The subscription service instance.
     *
     * @var \App\Services\SubscriptionService
     */
    protected $subscriptionService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\SubscriptionService  $subscriptionService
     * @return void
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for pending notifications...');
        
        // Get pending posts
        $pendingPosts = Blog::posts()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subWeeks(2))
            ->where('published_at', '<=', now())
            ->where('notification_sent', false)
            ->get();
            
        $postCount = 0;
        foreach ($pendingPosts as $post) {
            $this->info("Sending notifications for post: {$post->title}");
            $sentCount = $this->subscriptionService->notifySubscribers($post, 'post');
            if ($sentCount > 0) {
                $post->update(['notification_sent' => true]);
                $postCount++;
            }
        }
        
        // Get pending events
        $pendingEvents = Blog::events()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subWeeks(2))
            ->where('published_at', '<=', now())
            ->where('notification_sent', false)
            ->with('event')
            ->get();
            
        $eventCount = 0;
        foreach ($pendingEvents as $event) {
            $this->info("Sending notifications for event: {$event->title}");
            $sentCount = $this->subscriptionService->notifySubscribers($event, 'event');
            if ($sentCount > 0) {
                $event->update(['notification_sent' => true]);
                $eventCount++;
            }
        }
        
        $this->info("Notifications sent for {$postCount} posts and {$eventCount} events.");
        
        return Command::SUCCESS;
    }
}