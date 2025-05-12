@extends('layouts.web-pages')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb__area" style="background-image: url({{ asset('assets/img/banner/breadcrumb.jpg') }});">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>Upcoming Events</h2>
                <span>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ route('events.index') }}">
                        Events
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    Upcoming
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Upcoming Events Area Start -->
    <div class="blog__four section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-title text-center mb-5">
                        <h3>Upcoming Events</h3>
                        <p>Don't miss out on these exciting upcoming events</p>
                    </div>
                </div>
            </div>
            <div class="row gy-4">
                @forelse($eventPosts as $eventPost)
                    <div class="col-xl-4 col-md-6">
                        <div class="event__card">
                            <div class="event__card-img">
                                @if($eventPost->event && $eventPost->event->banner_image)
                                    <img src="{{ asset('storage/' . $eventPost->event->banner_image) }}" alt="{{ $eventPost->title }}">
                                @else
                                    <img src="{{ asset('storage/' . $eventPost->image) }}" alt="{{ $eventPost->title }}">
                                @endif
                                <div class="event__countdown" data-countdown="{{ $eventPost->event->start_date->format('Y-m-d') }}T{{ $eventPost->event->start_time }}">
                                    <span class="countdown-item">
                                        <span class="countdown-value" data-days>00</span>
                                        <span class="countdown-label">Days</span>
                                    </span>
                                    <span class="countdown-item">
                                        <span class="countdown-value" data-hours>00</span>
                                        <span class="countdown-label">Hours</span>
                                    </span>
                                    <span class="countdown-item">
                                        <span class="countdown-value" data-minutes>00</span>
                                        <span class="countdown-label">Min</span>
                                    </span>
                                </div>
                            </div>
                            <div class="event__card-content">
                                <div class="event__card-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $eventPost->event->formatted_start_date }}
                                </div>
                                <h3>
                                    <a href="{{ route('events.show', $eventPost->slug) }}">{{ $eventPost->title }}</a>
                                </h3>
                                <div class="event__card-meta">
                                    <span class="location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ Str::limit($eventPost->event->full_location, 30) }}
                                    </span>
                                    <span class="price">
                                        <i class="fas fa-ticket-alt"></i>
                                        {{ $eventPost->event->formatted_price }}
                                    </span>
                                </div>
                                <p>{{ $eventPost->getExcerpt(100) }}</p>
                                <div class="event__card-footer">
                                    @if($eventPost->event->registration_link)
                                        <a href="{{ $eventPost->event->registration_link }}" target="_blank" class="btn-one btn-sm">
                                            Register <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('events.show', $eventPost->slug) }}" class="btn-one btn-sm">
                                            Learn More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endif
                                    <span class="attendees">
                                        <i class="fas fa-users"></i>
                                        {{ $eventPost->event->max_attendees ? $eventPost->event->max_attendees . ' spots' : 'Unlimited' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="no-events text-center">
                            <i class="fas fa-calendar-times fa-4x mb-3"></i>
                            <h3>No Upcoming Events</h3>
                            <p>There are no upcoming events at the moment. Check back later for new events.</p>
                            <a href="{{ route('events.past') }}" class="btn-one">View Past Events <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($eventPosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination__area">
                    {{ $eventPosts->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Upcoming Events Area End -->
@endsection

@section('styles')
<style>
    .event__card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .event__card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .event__card-img {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .event__card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event__countdown {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.8);
        color: #fff;
        padding: 15px;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .countdown-item {
        text-align: center;
    }

    .countdown-value {
        display: block;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
    }

    .countdown-label {
        display: block;
        font-size: 12px;
        margin-top: 5px;
        opacity: 0.8;
    }

    .event__card-content {
        padding: 25px;
    }

    .event__card-date {
        color: var(--theme-color);
        font-size: 14px;
        margin-bottom: 10px;
    }

    .event__card-date i {
        margin-right: 5px;
    }

    .event__card h3 {
        margin-bottom: 15px;
        font-size: 20px;
    }

    .event__card h3 a {
        color: #333;
    }

    .event__card h3 a:hover {
        color: var(--theme-color);
    }

    .event__card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 14px;
        color: #666;
    }

    .event__card-meta i {
        margin-right: 5px;
        color: var(--theme-color);
    }

    .event__card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .btn-sm {
        padding: 8px 20px;
        font-size: 14px;
    }

    .attendees {
        font-size: 14px;
        color: #666;
    }

    .attendees i {
        margin-right: 5px;
        color: var(--theme-color);
    }

    .no-events {
        padding: 100px 0;
        color: #666;
    }

    .no-events i {
        color: #ddd;
    }

    .no-events h3 {
        margin-bottom: 15px;
    }

    .no-events p {
        margin-bottom: 30px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Countdown Timer Function
    document.addEventListener('DOMContentLoaded', function() {
        const countdowns = document.querySelectorAll('[data-countdown]');
        
        countdowns.forEach(countdown => {
            const targetDate = new Date(countdown.dataset.countdown).getTime();
            
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = targetDate - now;
                
                if (distance < 0) {
                    countdown.innerHTML = '<span class="countdown-expired">Event Started</span>';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                
                countdown.querySelector('[data-days]').textContent = String(days).padStart(2, '0');
                countdown.querySelector('[data-hours]').textContent = String(hours).padStart(2, '0');
                countdown.querySelector('[data-minutes]').textContent = String(minutes).padStart(2, '0');
            };
            
            updateCountdown();
            setInterval(updateCountdown, 60000); // Update every minute
        });
    });
</script>
@endsection