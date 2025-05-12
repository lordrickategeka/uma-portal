@extends('layouts.web-pages')

@section('content')
    <div class="breadcrumb__area" style="background-image: url(assets/img/banner/breadcrumb.jpg);">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>{{ $event->blog->title }}</h2>
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
                    {{ $event->blog->title }}
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <div class="event__details section-padding">
        <div class="container">
            <div class="row gy-4 flex-wrap-reverse">
                <div class="col-xl-8">
                    <!-- Event Banner Image -->
                    @if($event->banner_image)
                        <div class="event__details-banner">
                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->blog->title }}">
                        </div>
                    @else
                        <div class="event__details-thumb">
                            <img src="{{ asset('storage/' . $event->blog->image) }}" alt="{{ $event->blog->title }}">
                        </div>
                    @endif

                    <!-- Event Quick Info Bar -->
                    <div class="event__details-info-bar">
                        <div class="event__info-item">
                            <i class="far fa-calendar-alt"></i>
                            <div>
                                <span>Date</span>
                                <p>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                                @if($event->end_date && $event->end_date != $event->start_date)
                                    - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                @endif
                                </p>
                            </div>
                        </div>
                        <div class="event__info-item">
                            <i class="far fa-clock"></i>
                            <div>
                                <span>Time</span>
                                <p>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                @if($event->end_time)
                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                @endif
                                </p>
                            </div>
                        </div>
                        <div class="event__info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span>Location</span>
                                @if($event->is_virtual)
                                    <p>Virtual Event ({{ $event->virtual_platform }})</p>
                                @else
                                    <p>{{ $event->venue_name }}</p>
                                @endif
                            </div>
                        </div>
                        @if($event->ticket_price)
                            <div class="event__info-item">
                                <i class="fas fa-ticket-alt"></i>
                                <div>
                                    <span>Price</span>
                                    <p>{{ $event->ticket_currency }} {{ number_format($event->ticket_price, 2) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Event Content -->
                    <div class="event__details-content">
                        <h3>{{ $event->blog->title }}</h3>
                        <p>{!! $event->blog->content !!}</p>

                        <!-- Event Status Badge -->
                        @if($event->status == 'upcoming')
                            <div class="event__status-badge upcoming">
                                <i class="fas fa-calendar-check"></i> Upcoming Event
                            </div>
                        @elseif($event->status == 'ongoing')
                            <div class="event__status-badge ongoing">
                                <i class="fas fa-broadcast-tower"></i> Event in Progress
                            </div>
                        @elseif($event->status == 'completed')
                            <div class="event__status-badge completed">
                                <i class="fas fa-check-circle"></i> Event Completed
                            </div>
                        @elseif($event->status == 'cancelled')
                            <div class="event__status-badge cancelled">
                                <i class="fas fa-times-circle"></i> Event Cancelled
                            </div>
                        @endif

                        <!-- Venue Details -->
                        @if(!$event->is_virtual)
                            <div class="event__venue-details">
                                <h4>Venue Details</h4>
                                <div class="venue__info">
                                    <h5>{{ $event->venue_name }}</h5>
                                    <p>{{ $event->address }}</p>
                                    <p>{{ $event->city }}, {{ $event->country }}</p>
                                </div>
                                <!-- Optional Map Integration -->
                                <div class="venue__map" id="venue-map"></div>
                            </div>
                        @endif

                        <!-- Virtual Event Details -->
                        @if($event->is_virtual)
                            <div class="event__virtual-details">
                                <h4>Virtual Event Information</h4>
                                <div class="virtual__info">
                                    <p><strong>Platform:</strong> {{ $event->virtual_platform }}</p>
                                    @if($event->virtual_link && $event->status != 'completed')
                                        <p><strong>Access Link:</strong> 
                                            <a href="{{ $event->virtual_link }}" target="_blank" class="virtual-link">
                                                Join Event <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Registration Section -->
                        @if($event->status != 'completed' && $event->status != 'cancelled')
                            <div class="event__registration">
                                <h4>Event Registration</h4>
                                @if($event->max_attendees)
                                    <p class="attendees-info">
                                        <i class="fas fa-users"></i> 
                                        Limited to {{ $event->max_attendees }} attendees
                                    </p>
                                @endif
                                @if($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="btn-one registration-btn">
                                        Register Now <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                        <!-- Blog Quote if exists -->
                        @if($event->blog->quote)
                            <div class="blog__details-quote">
                                <p>{{ $event->blog->quote }}</p>
                                <h5>- {{ $event->blog->quote_author }}</h5>
                                <span>{{ $event->blog->quote_position }}</span>
                            </div>
                        @endif

                        <!-- Display associated images -->
                        @if($event->blog->images && $event->blog->images->count() > 0)
                            <div class="event__details-gallery">
                                <h4>Event Gallery</h4>
                                <div class="gallery__grid">
                                    @foreach($event->blog->images as $image)
                                        <div class="gallery__item">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Event Image">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Additional Content -->
                        @if($event->blog->additional_content)
                            <div class="event__additional-content">
                                <p>{{ $event->blog->additional_content }}</p>
                            </div>
                        @endif

                        <!-- Tags -->
                        <div class="sidebar-item-single sidebar-blog-tags">
                            <div class="sidebar-item-single-title">
                                <h5>Tags</h5>
                            </div>
                            <div class="tags">
                                @foreach($event->blog->tags as $tag)
                                    {{ $tag->name }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Event Share Section -->
                    <div class="event__share">
                        <h4>Share This Event</h4>
                        <div class="social__share">
                            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                @include('inc.single_page_sidebar')
            </div>
        </div>
    </div>

    <!-- Subscribe Section -->
    <div class="subscribe__one" style="background-image: url('assets/img/subscribe/subscribe-bg.png');">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="subscribe__one-title text-center">
                        <h2>Stay Connected! Subscribe For <span>The Latest Updates</span></h2>
                    </div>
                    <form action="#" class="subscribe__one-form">
                        <input type="email" placeholder="Enter Your Email">
                        <button class="btn-one" type="submit">Subscribe now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .event__details-info-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .event__info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 200px;
    }

    .event__info-item i {
        font-size: 24px;
        color: var(--theme-color);
    }

    .event__info-item span {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 2px;
    }

    .event__info-item p {
        margin: 0;
        font-weight: 600;
        color: #333;
    }

    .event__status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        margin: 15px 0;
    }

    .event__status-badge.upcoming {
        background: #e3f2fd;
        color: #1976d2;
    }

    .event__status-badge.ongoing {
        background: #e8f5e9;
        color: #388e3c;
    }

    .event__status-badge.completed {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .event__status-badge.cancelled {
        background: #ffebee;
        color: #d32f2f;
    }

    .event__venue-details,
    .event__virtual-details,
    .event__registration {
        margin: 30px 0;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .venue__map {
        height: 300px;
        width: 100%;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    .virtual-link {
        color: var(--theme-color);
        font-weight: 600;
    }

    .attendees-info {
        margin-bottom: 15px;
        color: #666;
    }

    .registration-btn {
        margin-top: 10px;
    }

    .event__details-gallery {
        margin: 30px 0;
    }

    .gallery__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .gallery__item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }

    .event__share {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #e0e0e0;
    }

    .social__share {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .social__share a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        transition: all 0.3s;
    }

    .social__share a.facebook {
        background: #3b5998;
    }

    .social__share a.twitter {
        background: #1da1f2;
    }

    .social__share a.linkedin {
        background: #0077b5;
    }

    .social__share a.email {
        background: #ea4335;
    }

    .social__share a:hover {
        transform: translateY(-3px);
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize map if venue is physical
    @if(!$event->is_virtual)
        // Add your map initialization code here
        // You can use Google Maps, Leaflet, or any other mapping library
    @endif
</script>
@endsection