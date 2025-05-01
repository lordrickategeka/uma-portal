@extends('layouts.dash')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h1>Events</h1>
        @can('create', App\Models\Event::class)
            <a href="{{ route('events.create') }}" class="btn btn-primary">Create Event</a>
        @endcan
    </div>

    @foreach ($events as $event)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $event->title }}</h5>
                <p class="card-text">{{ $event->summary }}</p>
                <p><strong>Start Date:</strong> {{ $event->start_date }}</p>
                <p><strong>Location:</strong> {{ $event->venue_name }} | {{ $event->city }}</p>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-info">View</a>

                    @can('update', $event)
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">Edit</a>
                    @endcan

                    @can('delete', $event)
                        <form action="{{ route('events.destroy', $event) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endcan

                    @can('publish', $event)
                        <form action="{{ route('events.publish', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Publish</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @endforeach

    {{ $events->links() }}
</div>
@endsection
