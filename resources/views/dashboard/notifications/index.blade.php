@extends('layouts.dash')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subscriber Notifications</h1>
        @if($pendingPosts->count() > 0 || $pendingEvents->count() > 0)
            <a href="{{ route('notifications.send-all') }}" 
               class="d-none d-sm-inline-block btn btn-primary shadow-sm"
               onclick="return confirm('Send notifications for all pending content to all subscribers?');">
                <i class="fas fa-paper-plane fa-sm text-white-50 mr-1"></i> Send All Notifications
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPosts->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pending Events</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingEvents->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Notified Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notifiedPostsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Notified Events</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notifiedEventsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Posts -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pending Post Notifications</h6>
        </div>
        <div class="card-body">
            @if($pendingPosts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Published</th>
                                <th>Author</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPosts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->published_at->format('M d, Y') }}</td>
                                    <td>{{ $post->author->name ?? 'Unknown' }}</td>
                                    <td>
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" 
                                                 alt="{{ $post->title }}" 
                                                 style="max-height: 50px; max-width: 80px;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('notifications.send-post', $post->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           onclick="return confirm('Send notification for this post to all subscribers?');">
                                            <i class="fas fa-paper-plane"></i> Send Notification
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No pending post notifications</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pending Events -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pending Event Notifications</h6>
        </div>
        <div class="card-body">
            @if($pendingEvents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Published</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Banner</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingEvents as $event)
                                <tr>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->published_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($event->event && $event->event->start_date)
                                            {{ $event->event->start_date->format('M d, Y') }}
                                            @if($event->event->end_date && $event->event->end_date->format('Y-m-d') !== $event->event->start_date->format('Y-m-d'))
                                                <br>to {{ $event->event->end_date->format('M d, Y') }}
                                            @endif
                                        @else
                                            <span class="text-muted">No date set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->event)
                                            @if($event->event->is_virtual)
                                                Virtual Event
                                                @if($event->event->virtual_platform)
                                                    ({{ $event->event->virtual_platform }})
                                                @endif
                                            @elseif($event->event->venue_name)
                                                {{ $event->event->venue_name }}
                                            @else
                                                <span class="text-muted">No venue set</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No event details</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->event && $event->event->banner_image)
                                            <img src="{{ asset('storage/' . $event->event->banner_image) }}" 
                                                 alt="{{ $event->title }}" 
                                                 style="max-height: 50px; max-width: 80px;">
                                        @elseif($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" 
                                                 alt="{{ $event->title }}" 
                                                 style="max-height: 50px; max-width: 80px;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('notifications.send-event', $event->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           onclick="return confirm('Send notification for this event to all subscribers?');">
                                            <i class="fas fa-paper-plane"></i> Send Notification
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No pending event notifications</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection