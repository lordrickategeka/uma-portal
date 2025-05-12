@extends('layouts.web-pages')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb__area" style="background-image: url({{ asset('assets/img/banner/breadcrumb.jpg') }});">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>All Events</h2>
                <span>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="#">
                        Events
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    All Events
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- All Events Area Start -->
    <div class="events__all section-padding">
        <div class="container">
            <!-- Advanced Filters -->
            <div class="events__filters mb-5">
                <form action="{{ route('events.all') }}" method="GET" class="advanced-filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="event_status" class="form-control">
                                <option value="">All Status</option>
                                <option value="upcoming" {{ request('event_status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('event_status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('event_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('event_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="event_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="physical" {{ request('event_type') == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="virtual" {{ request('event_type') == 'virtual' ? 'selected' : '' }}>Virtual</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" placeholder="End Date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn-one w-100">Filter</button>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <input type="text" name="city" class="form-control" placeholder="City" value="{{ request('city') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="free_only" name="free_only" value="1" {{ request('free_only') ? 'checked' : '' }}>
                                <label class="form-check-label" for="free_only">Free Events Only</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-control">
                                <option value="event_date" {{ request('sort_by') == 'event_date' ? 'selected' : '' }}>Sort by Date</option>
                                <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>Recently Added</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                                <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select name="sort_order" class="form-control">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>ASC</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>DESC</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('events.all') }}" class="btn-one secondary w-100">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="results-summary mb-4">
                {{-- <h5>{{ $eventPosts->total() }} events found</h5> --}}
            </div>

            <!-- Events List View -->
            <div class="events__list">
                @forelse($eventPosts as $eventPost)
                    <div class="event__list-item">
                        <div class="event__list-image">
                            @if($eventPost->event && $eventPost->event->banner_image)
                                <img src="{{ asset('storage/' . $eventPost->event->banner_image) }}" alt="{{ $eventPost->title }}">
                            @else
                                <img src="{{ asset('storage/' . $eventPost->image) }}" alt="{{ $eventPost->title }}">
                            @endif
                            <div class="event__badge {{ $eventPost->event->status }}">
                                {{ ucfirst($eventPost->event->status) }}
                            </div>
                        </div>
                        <div class="event__list-content">
                            <div class="event__list-header">
                                <h3>
                                    <a href="{{ route('events.show', $eventPost->slug) }}">{{ $eventPost->title }}</a>
                                </h3>
                                <div class="event__price-tag">
                                    {{ $eventPost->event->formatted_price }}
                                </div>
                            </div>
                            <div class="event__list-meta">
                                <span>
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $eventPost->event->formatted_start_date }}
                                    @if($eventPost->event->end_date && $eventPost->event->end_date != $eventPost->event->start_date)
                                        - {{ $eventPost->event->end_date->format('M d, Y') }}
                                    @endif
                                </span>
                                <span>
                                    <i class="far fa-clock"></i>
                                    {{ $eventPost->event->formatted_start_time }}
                                </span>
                                <span>
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $eventPost->event->full_location }}
                                </span>
                                @if($eventPost->event->max_attendees)
                                    <span>
                                        <i class="fas fa-users"></i>
                                        {{ $eventPost->event->max_attendees }} spots
                                    </span>
                                @endif
                            </div>
                            <p>{{ $eventPost->getExcerpt(200) }}</p>
                            <div class="event__list-footer">
                                <div class="event__categories">
                                    @foreach($eventPost->categories->take(2) as $category)
                                        <span class="category-tag">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <div class="event__actions">
                                    @if($eventPost->event->registration_link && $eventPost->event->status != 'completed' && $eventPost->event->status != 'cancelled')
                                        <a href="{{ $eventPost->event->registration_link }}" target="_blank" class="btn-one btn-sm">
                                            Register <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('events.show', $eventPost->slug) }}" class="btn-outline btn-sm">
                                        Details <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-events text-center">
                        <i class="fas fa-calendar-times fa-4x mb-3"></i>
                        <h3>No Events Found</h3>
                        <p>Try adjusting your search filters or check back later for new events.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($eventPosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination__area">
                    {{ $eventPosts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- All Events Area End -->
@endsection

@section('styles')
<style>
    .events__filters {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .advanced-filter-form .form-control,
    .advanced-filter-form .btn-one {
        height: 40px;
    }

    .form-check {
        height: 40px;
        display: flex;
        align-items: center;
    }

    .btn-one.secondary {
        background: #6c757d;
    }

    .btn-one.secondary:hover {
        background: #5a6268;
    }

    .results-summary {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 10px;
    }

    .event__list-item {
        display: flex;
        gap: 30px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .event__list-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 35px rgba(0,0,0,0.15);
    }

    .event__list-image {
        width: 300px;
        height: 250px;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .event__list-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event__badge {
        position: absolute;
        top: 20px;
        left: 20px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .event__badge.upcoming {
        background: #e3f2fd;
        color: #1976d2;
    }

    .event__badge.ongoing {
        background: #e8f5e9;
        color: #388e3c;
    }

    .event__badge.completed {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .event__badge.cancelled {
        background: #ffebee;
        color: #d32f2f;
    }

    .event__list-content {
        flex: 1;
        padding: 25px;
        display: flex;
        flex-direction: column;
    }

    .event__list-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .event__list-header h3 {
        margin: 0;
        font-size: 22px;
        flex: 1;
    }

    .event__list-header h3 a {
        color: #333;
    }

    .event__list-header h3 a:hover {
        color: var(--theme-color);
    }

    .event__price-tag {
        background: var(--theme-color);
        color: #fff;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }

    .event__list-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 14px;
        color: #666;
    }

    .event__list-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .event__list-meta i {
        color: var(--theme-color);
    }

    .event__list-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .event__categories {
        display: flex;
        gap: 10px;
    }

    .category-tag {
        background: #f0f0f0;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        color: #666;
    }

    .event__actions {
        display: flex;
        gap: 10px;
    }

    .btn-sm {
        padding: 8px 20px;
        font-size: 14px;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--theme-color);
        color: var(--theme-color);
    }

    .btn-outline:hover {
        background: var(--theme-color);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .event__list-item {
            flex-direction: column;
        }

        .event__list-image {
            width: 100%;
            height: 300px;
        }

        .event__list-header {
            flex-direction: column;
            gap: 10px;
        }

        .event__price-tag {
            align-self: flex-start;
        }

        .event__list-footer {
            flex-direction: column;
            gap: 15px;
        }

        .event__categories {
            align-self: flex-start;
        }

        .event__actions {
            align-self: flex-end;
        }
    }

    @media (max-width: 576px) {
        .event__list-meta {
            flex-direction: column;
            gap: 10px;
        }

        .event__actions {
            flex-direction: column;
            width: 100%;
        }

        .event__actions .btn-sm {
            width: 100%;
        }
    }
</style>
@endsection