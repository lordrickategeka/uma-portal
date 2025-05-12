@extends('layouts.web-pages')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb__area" style="background-image: url({{ asset('assets/img/banner/breadcrumb.jpg') }});">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>Past Events</h2>
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
                    Past
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Past Events Area Start -->
    <div class="blog__four section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-title text-center mb-5">
                        <h3>Past Events</h3>
                        <p>Relive the memories from our previous events</p>
                    </div>
                </div>
            </div>
            
            <div class="past-events-timeline">
                @forelse($eventPosts as $eventPost)
                    <div class="timeline-event {{ $loop->even ? 'right' : 'left' }}">
                        <div class="timeline-event-content">
                            <div class="event-date">
                                <span class="day">{{ $eventPost->event->start_date->format('d') }}</span>
                                <span class="month">{{ $eventPost->event->start_date->format('M') }}</span>
                                <span class="year">{{ $eventPost->event->start_date->format('Y') }}</span>
                            </div>
                            <div class="event-card">
                                <div class="event-image">
                                    @if($eventPost->event && $eventPost->event->banner_image)
                                        <img src="{{ asset('storage/' . $eventPost->event->banner_image) }}" alt="{{ $eventPost->title }}">
                                    @else
                                        <img src="{{ asset('storage/' . $eventPost->image) }}" alt="{{ $eventPost->title }}">
                                    @endif
                                    <div class="event-overlay">
                                        <a href="{{ route('events.show', $eventPost->slug) }}" class="view-btn">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                                <div class="event-info">
                                    <h3>
                                        <a href="{{ route('events.show', $eventPost->slug) }}">{{ $eventPost->title }}</a>
                                    </h3>
                                    <div class="event-meta">
                                        <span>
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ Str::limit($eventPost->event->full_location, 40) }}
                                        </span>
                                        <span>
                                            <i class="fas fa-users"></i>
                                            {{ $eventPost->event->max_attendees ?? 'Unlimited' }} attendees
                                        </span>
                                    </div>
                                    <p>{{ $eventPost->getExcerpt(120) }}</p>
                                    <div class="event-stats">
                                        <div class="stat">
                                            <i class="fas fa-eye"></i>
                                            <span>{{ $eventPost->views }} views</span>
                                        </div>
                                        @if($eventPost->categories->count() > 0)
                                            <div class="stat">
                                                <i class="fas fa-tag"></i>
                                                <span>{{ $eventPost->categories->first()->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-events text-center">
                        <i class="fas fa-calendar-check fa-4x mb-3"></i>
                        <h3>No Past Events</h3>
                        <p>We don't have any past events yet. Check out our upcoming events!</p>
                        <a href="{{ route('events.upcoming') }}" class="btn-one">View Upcoming Events <i class="fas fa-chevron-right"></i></a>
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
    <!-- Past Events Area End -->
@endsection

@section('styles')
<style>
    .past-events-timeline {
        position: relative;
        padding: 40px 0;
    }

    .past-events-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 100%;
        background: #e0e0e0;
    }

    .timeline-event {
        position: relative;
        margin-bottom: 50px;
    }

    .timeline-event::before {
        content: '';
        position: absolute;
        top: 30px;
        width: 15px;
        height: 15px;
        background: var(--theme-color);
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e0e0e0;
        z-index: 1;
    }

    .timeline-event.left::before {
        right: -7.5px;
    }

    .timeline-event.right::before {
        left: -7.5px;
    }

    .timeline-event-content {
        width: 45%;
        position: relative;
    }

    .timeline-event.left .timeline-event-content {
        margin-left: auto;
        margin-right: 40px;
    }

    .timeline-event.right .timeline-event-content {
        margin-left: 40px;
    }

    .event-date {
        position: absolute;
        top: 20px;
        width: 80px;
        text-align: center;
        background: var(--theme-color);
        color: #fff;
        padding: 10px 5px;
        border-radius: 5px;
        z-index: 2;
    }

    .timeline-event.left .event-date {
        right: -100px;
    }

    .timeline-event.right .event-date {
        left: -100px;
    }

    .event-date span {
        display: block;
        line-height: 1.2;
    }

    .event-date .day {
        font-size: 24px;
        font-weight: 700;
    }

    .event-date .month {
        font-size: 14px;
        text-transform: uppercase;
        margin: 5px 0;
    }

    .event-date .year {
        font-size: 12px;
        opacity: 0.8;
    }

    .event-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }

    .event-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .event-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .event-card:hover .event-overlay {
        opacity: 1;
    }

    .event-card:hover .event-image img {
        transform: scale(1.1);
    }

    .view-btn {
        color: #fff;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border: 2px solid #fff;
        border-radius: 30px;
        transition: all 0.3s;
    }

    .view-btn:hover {
        background: #fff;
        color: var(--theme-color);
    }

    .event-info {
        padding: 25px;
    }

    .event-info h3 {
        margin-bottom: 15px;
        font-size: 20px;
    }

    .event-info h3 a {
        color: #333;
    }

    .event-info h3 a:hover {
        color: var(--theme-color);
    }

    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 14px;
        color: #666;
    }

    .event-meta i {
        margin-right: 5px;
        color: var(--theme-color);
    }

    .event-stats {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e0e0e0;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #666;
    }

    .stat i {
        color: var(--theme-color);
    }

    .no-events {
        padding: 100px 0;
        color: #666;
    }

    .no-events i {
        color: #ddd;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .past-events-timeline::before {
            left: 30px;
        }

        .timeline-event::before {
            left: 23px !important;
            right: auto !important;
        }

        .timeline-event-content {
            width: calc(100% - 80px);
            margin-left: 80px !important;
            margin-right: 0 !important;
        }

        .event-date {
            left: -50px !important;
            right: auto !important;
        }
    }

    @media (max-width: 576px) {
        .event-date {
            position: static;
            width: 100%;
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .event-date span {
            display: inline-block;
        }

        .timeline-event-content {
            width: calc(100% - 50px);
            margin-left: 50px !important;
        }
    }
</style>
@endsection