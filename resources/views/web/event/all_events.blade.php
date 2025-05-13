@extends('layouts.web-pages')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb__area" style="background-image: url({{ asset('assets/img/banner/breadcrumb.jpg') }});">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>Events</h2>
                <span>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    Events
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Events Area Start -->
    <div class="blog__four section-padding">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-8">
                    <!-- Events Filter Bar -->
                    <div class="events__filter-bar mb-4">
                        <form action="#" method="GET" class="filter-form">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="event_status" class="form-control">
                                        <option value="">All Events</option>
                                        <option value="upcoming"
                                            {{ request('event_status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="ongoing"
                                            {{ request('event_status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed"
                                            {{ request('event_status') == 'completed' ? 'selected' : '' }}>Completed
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="event_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="physical"
                                            {{ request('event_type') == 'physical' ? 'selected' : '' }}>Physical Events
                                        </option>
                                        <option value="virtual" {{ request('event_type') == 'virtual' ? 'selected' : '' }}>
                                            Virtual Events</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn-one w-100">Filter <i
                                            class="fas fa-filter"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Events Grid -->
                    <div class="row gy-4">
                        @forelse($eventPosts as $eventPost)
                            <div class="col-md-6">
                                <div class="blog__four-single-blog">
                                    <div class="blog__four-single-blog-img">
                                        @if ($eventPost->event && $eventPost->banner_image_url)
                                            <img src="{{$eventPost->banner_image_url }}"
                                                alt="{{ $eventPost->title }}">
                                        @else
                                            <img src="{{ $eventPost->banner_image_url}}"
                                                alt="{{ $eventPost->title }}">
                                        @endif
                                        @if ($eventPost->event && $eventPost->event->start_date)
                                            <div class="blog__four-single-blog-date">
                                                <span
                                                    class="date">{{ $eventPost->event->start_date->format('d') }}</span>
                                                <span
                                                    class="month">{{ $eventPost->event->start_date->format('M') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="blog__four-single-blog-content">
                                        <!-- Event Meta -->
                                        <div class="event__meta">
                                            <span
                                                class="event-type {{ $eventPost->event->is_virtual ? 'virtual' : 'physical' }}">
                                                <i
                                                    class="fas {{ $eventPost->event->is_virtual ? 'fa-video' : 'fa-map-marker-alt' }}"></i>
                                                {{ $eventPost->event->is_virtual ? 'Virtual Event' : $eventPost->event->city }}
                                            </span>
                                            <span class="event-price">
                                                <i class="fas fa-ticket-alt"></i>
                                                {{ $eventPost->event->formatted_price }}
                                            </span>
                                        </div>

                                        <h3>
                                            <a
                                                href="{{ route('blog.show', ['id' => $eventPost->id, 'slug' => $eventPost->slug]) }}">{{ $eventPost->title }}</a>
                                        </h3>
                                        <p>{{ $eventPost->getExcerpt(100) }}</p>

                                        <div class="blog__four-single-blog-content-bottom">
                                            <div class="blog__four-single-blog-content-bottom-left">
                                                <span><i class="far fa-user"></i>{{ $eventPost->author->name }}</span>
                                                <span><i
                                                        class="far fa-clock"></i>{{ $eventPost->event->formatted_start_time }}</span>
                                            </div>
                                            <div class="blog__four-single-blog-content-bottom-right">
                                                <a
                                                    href="{{ route('blog.show', ['id' => $eventPost->id, 'slug' => $eventPost->slug]) }}">Read
                                                    More <i class="fas fa-chevron-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="no-events text-center">
                                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                    <h3>No events found</h3>
                                    <p>There are no events matching your criteria.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($eventPosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination__area">
                            {{ $eventPosts->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                @include('inc.single_page_sidebar')
            </div>
        </div>
    </div>
    <!-- Events Area End -->
@endsection

@section('styles')
    <style>
        .events__filter-bar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .event__meta {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }

        .event__meta span {
            font-size: 14px;
            color: #666;
        }

        .event-type.virtual {
            color: #3B82F6;
        }

        .event-type.physical {
            color: #10B981;
        }

        .event-price {
            color: #EF4444;
        }

        .blog__four-single-blog-date {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--theme-color);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .blog__four-single-blog-date .date {
            display: block;
            font-size: 20px;
            font-weight: 700;
        }

        .blog__four-single-blog-date .month {
            display: block;
            font-size: 14px;
            text-transform: uppercase;
        }

        .no-events {
            padding: 60px 0;
            color: #666;
        }
    </style>
@endsection
