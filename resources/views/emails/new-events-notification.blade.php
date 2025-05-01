<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Event: {{ $blog->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .event-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .event-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .event-details h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .event-detail {
            margin-bottom: 10px;
        }
        .event-detail i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            color: #007bff;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .unsubscribe {
            display: inline-block;
            color: #6c757d;
            text-decoration: none;
            margin-top: 20px;
            font-size: 12px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Event Announcement</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $subscriber->name ?? 'there' }},</p>
        
        <p>We're excited to announce a new event:</p>
        
        <h2>{{ $blog->title }}</h2>
        
        @if($blog->event->banner_image)
            <img src="{{ asset('storage/' . $blog->event->banner_image) }}" alt="{{ $blog->title }}" class="event-image">
        @elseif($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="event-image">
        @endif
        
        <p>{{ $excerpt }}</p>
        
        <div class="event-details">
            <h3>Event Details</h3>
            
            @if($blog->event->start_date)
                <div class="event-detail">
                    <i class="far fa-calendar"></i> <strong>Date:</strong> 
                    {{ $blog->event->start_date->format('F j, Y') }}
                    @if($blog->event->end_date && $blog->event->start_date->format('Y-m-d') !== $blog->event->end_date->format('Y-m-d'))
                        - {{ $blog->event->end_date->format('F j, Y') }}
                    @endif
                </div>
            @endif
            
            @if($blog->event->start_time)
                <div class="event-detail">
                    <i class="far fa-clock"></i> <strong>Time:</strong> 
                    {{ \Carbon\Carbon::parse($blog->event->start_time)->format('g:i A') }}
                    @if($blog->event->end_time)
                        - {{ \Carbon\Carbon::parse($blog->event->end_time)->format('g:i A') }}
                    @endif
                </div>
            @endif
            
            @if($blog->event->is_virtual)
                <div class="event-detail">
                    <i class="fas fa-video"></i> <strong>Virtual Event:</strong> 
                    @if($blog->event->virtual_platform)
                        Via {{ $blog->event->virtual_platform }}
                    @else
                        Details will be provided
                    @endif
                </div>
            @else
                @if($blog->event->venue_name)
                    <div class="event-detail">
                        <i class="fas fa-map-marker-alt"></i> <strong>Venue:</strong> {{ $blog->event->venue_name }}
                    </div>
                @endif
                
                @if($blog->event->address)
                    <div class="event-detail">
                        <i class="fas fa-location-arrow"></i> <strong>Address:</strong> 
                        {{ $blog->event->address }}
                        @if($blog->event->city) 
                            , {{ $blog->event->city }}
                        @endif
                        @if($blog->event->country)
                            , {{ $blog->event->country }}
                        @endif
                    </div>
                @endif
            @endif
            
            @if($blog->event->registration_link)
                <div class="event-detail">
                    <i class="fas fa-ticket-alt"></i> <strong>Registration:</strong> 
                    <a href="{{ $blog->event->registration_link }}">Register here</a>
                </div>
            @endif
            
            @if($blog->event->ticket_price > 0)
                <div class="event-detail">
                    <i class="fas fa-dollar-sign"></i> <strong>Price:</strong> 
                    {{ number_format($blog->event->ticket_price, 2) }} {{ $blog->event->ticket_currency ?? '' }}
                </div>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ url('events/' . $blog->slug) }}" class="button">View Event Details</a>
        </div>
        
        <p>We hope to see you there!</p>
        
        <p>Best regards,<br>The Team</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
        <p>
            This email was sent to {{ $subscriber->email }} because you subscribed to our newsletter.
            <a href="{{ route('subscription.unsubscribe', $subscriber->subscription_token) }}" class="unsubscribe">Unsubscribe</a>
        </p>
    </div>
</body>
</html>