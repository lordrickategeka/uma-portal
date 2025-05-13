<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'post_type',
        'slug',
        'content',
        'branch_id',
        'image',
        'author_id',
        'status',
        'published_at',
        'views',
        'notification_sent',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'notification_sent' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->title);
    }

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    // Scope a query to only include published posts.
    public function scopePublished($query)
    {
        return $query->where('blogs.status', 'published')
            ->whereNotNull('blogs.published_at')
            ->where('blogs.published_at', '<=', now());
    }


    // Scope a query to only include posts published within the last two weeks.
    public function scopeRecentlyPublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subWeeks(2))
            ->where('published_at', '<=', now());
    }

    // Scope a query to only include posts that need notifications.
    public function scopeNeedsNotification($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '>=', now()->subWeeks(2))
            ->where('published_at', '<=', now())
            ->where('notification_sent', false);
    }

    public function scopePosts($query)
    {
        return $query->where('post_type', 'post');
    }

    public function scopeEvents($query)
    {
        return $query->where('blogs.post_type', 'event');
    }

    public function getExcerpt($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::disk('news_images')->url($this->image);
        }
        
        return null;
    }
}
