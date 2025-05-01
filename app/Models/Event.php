<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue_name',
        'address',
        'city',
        'country',
        'is_virtual',
        'virtual_platform',
        'virtual_link',
        'registration_link',
        'max_attendees',
        'ticket_price',
        'ticket_currency',
        'banner_image',
        'status',
        'published_at',
        'notification_sent'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_virtual' => 'boolean',
        'max_attendees' => 'integer',
        'ticket_price' => 'decimal:2',
        'published_at' => 'datetime',
        'notification_sent' => 'boolean'
    ];

    protected $attributes = [
        'status' => 'draft',
        'is_virtual' => false,
        'notification_sent' => false
    ];

    // Relationships
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    // Accessors
    public function getSlugAttribute($value)
    {
        // Note: 'title' field doesn't exist in schema, might need to use 'venue_name' or blog title
        return $value ?: \Str::slug($this->blog->title ?? $this->venue_name);
    }

    /**
     * Scope to get published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope to get upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
                     ->orWhere(function($q) {
                         $q->where('start_date', '=', now()->toDateString())
                           ->where('start_time', '>', now()->toTimeString());
                     });
    }

    /**
     * Scope to get past events.
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now())
                     ->orWhere(function($q) {
                         $q->where('end_date', '=', now()->toDateString())
                           ->where('end_time', '<', now()->toTimeString());
                     });
    }

    /**
     * Get the formatted start date for the event.
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('l, F j, Y') : null;
    }

    /**
     * Get the formatted start time for the event.
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('g:i A') : null;
    }

    /**
     * Get the formatted end time for the event.
     */
    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('g:i A') : null;
    }

    /**
     * Get the formatted price with currency.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->ticket_price === null) {
            return 'Free';
        }
        
        return $this->ticket_currency . ' ' . number_format($this->ticket_price, 2);
    }

    /**
     * Get the full location attribute.
     */
    public function getFullLocationAttribute()
    {
        if ($this->is_virtual) {
            return 'Virtual Event' . ($this->virtual_platform ? ' via ' . $this->virtual_platform : '');
        }

        $parts = array_filter([
            $this->venue_name,
            $this->address,
            $this->city,
            $this->country
        ]);

        return !empty($parts) ? implode(', ', $parts) : 'Location TBD';
    }

    /**
     * Check if the event has started.
     */
    public function hasStarted()
    {
        if (!$this->start_date || !$this->start_time) {
            return false;
        }
        
        $startDateTime = $this->start_date->copy()->setTimeFromTimeString($this->start_time);
        return $startDateTime->isPast();
    }

    /**
     * Check if the event has ended.
     */
    public function hasEnded()
    {
        if (!$this->end_date || !$this->end_time) {
            return false;
        }
        
        $endDateTime = $this->end_date->copy()->setTimeFromTimeString($this->end_time);
        return $endDateTime->isPast();
    }

    /**
     * Check if the event is currently happening.
     */
    public function isHappening()
    {
        return $this->hasStarted() && !$this->hasEnded();
    }

    /**
     * Check if the event is published.
     */
    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->isPast();
    }

    /**
     * Publish the event.
     */
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = now();
        $this->save();
    }

    /**
     * Mark notification as sent.
     */
    public function markNotificationAsSent()
    {
        $this->notification_sent = true;
        $this->save();
    }
}