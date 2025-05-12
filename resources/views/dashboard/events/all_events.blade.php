@extends('layouts.dash')
@section('content')
    <div class="container">
        <h1>Events</h1>

        <a href="#" class="btn btn-primary mb-3">Create Event</a>

        <div class="card">
            <table class="table table-hover">
                <tr class="border-t">
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>Title</th>
                    <th>Event Details</th>
                    <th>Category / Tags</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                @foreach ($events as $index => $event)
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_ids[]" value="{{ $event->id }}" class="order-select">
                            {{ $index + 1 }}
                        </td>
                        <td>
                            <a target="_blank"
                                href="{{ route('events.showEvent', ['slug' => $event->slug]) }}">{{ $event->title }}</a>
                            <p><strong>Type:</strong> <span class="badge bg-success text-dark">
                                    {{ ucfirst($event->post_type) }}</span></p>
                        </td>
                        <td>
                            @if($event->event)
                                <p><strong>Date:</strong> {{ $event->event->formatted_start_date }}</p>
                                <p><strong>Time:</strong> {{ $event->event->formatted_start_time }}</p>
                                <p><strong>Location:</strong> {{ $event->event->full_location }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-{{ $event->event->status == 'upcoming' ? 'primary' : ($event->event->status == 'ongoing' ? 'warning' : ($event->event->status == 'completed' ? 'success' : 'danger')) }}">
                                        {{ ucfirst($event->event->status) }}
                                    </span>
                                </p>
                            @else
                                <span class="text-muted">No event details</span>
                            @endif
                        </td>
                        <td>
                            @if ($event->categories && count($event->categories))
                                <small>Categories:</small>
                                @foreach ($event->categories as $category)
                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No Categories</span>
                            @endif
                            <hr />
                            @if ($event->tags && $event->tags->count())
                                <small>Tags:</small>
                                @foreach ($event->tags as $tag)
                                    <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No Tags</span>
                            @endif
                        </td>
                        <td>{{ $event->branch->name ?? 'Not assigned' }}</td>
                        <td>{{ ucfirst($event->status) }}</td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $event->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    &#8942; <!-- HTML entity for vertical ellipsis -->
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdownMenuButton{{ $event->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('events.show', $event->id) }}">
                                            <i class="fas fa-eye me-2"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('events.show', $event->id)}}">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('event.destroy', $event->id) }}" method="POST" style="display:inline;"
                                            onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        {{ $events->links('vendor.pagination.simple-tailwind') }}
    </div>
    
    <script>
        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this event?')) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.order-select');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    </script>
@endsection