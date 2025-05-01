@extends('layouts.dash')

@section('content')
<div class="container">
    <h1>Edit Event</h1>
    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" class="form-control">{{ old('summary', $event->summary) }}</textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $event->start_date) }}">
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $event->end_date) }}">
        </div>

        <div class="form-group">
            <label for="venue_name">Venue Name</label>
            <input type="text" id="venue_name" name="venue_name" class="form-control" value="{{ old('venue_name', $event->venue_name) }}">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="draft" {{ $event->status == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $event->status == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>
@endsection
