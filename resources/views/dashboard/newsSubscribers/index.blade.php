@extends('layouts.dash')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="h3 mb-3"><strong>All Subscribers</strong></h1>


    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Subscribed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscribers as $index => $subscriber)
                <tr>
                    <td>
                        <input type="checkbox" name="selected_ids[]" value="{{ $subscriber->id }}" class="subscriber-select">
                        {{ $subscribers->firstItem() + $index }}
                    </td>
                    <td>{{ $subscriber->email }}</td>
                    <td>
                        @if($subscriber->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $subscriber->created_at->format('M d, Y') }}</td>
                    <td>
                        <form action="{{route('subscribers.destroy',$subscriber->id )}}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subscriber?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="card-footer">
            {{ $subscribers->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        var checkboxes = document.getElementsByClassName('subscriber-select');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    });
</script>
@endpush