@extends('layouts.dash')

@section('content')
<div class="container">
    <h1>{{ $event->title }}</h1>
    <p><strong>Summary:</strong> {{ $event->summary }}</p>
    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Start Date:</strong> {{ $event->start_date }}</p>
    <p><strong>End Date:</strong> {{ $event->end_date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue_name }} | {{ $event->city }}</p>
    
    @can('publish', $event)
        <form action="{{ route('events.publish', $event) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Publish Event</button>
        </form>
    @endcan

    @can('update', $event)
        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">Edit Event</a>
    @endcan
</div>
@endsection
