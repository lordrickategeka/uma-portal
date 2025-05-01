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
    <h1 class="h3 mb-3"><strong>All Memebrs</strong></h1>

    <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Add New Member</a>

    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Employer</th>
                    <th>Category</th>
                    <th>Specialization</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>
                        <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}" class="order-select">
                        {{ $index + 1 }}
                    </td>
                    <td>{{ $user->first_name }}
                        <small>{{ $user->last_name }}</small>
                    </td>
                    
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profile ? $user->profile->gender : 'N/A' }}</td>
                    <td>{{ $user->profile ? $user->profile->phone : 'N/A' }}</td>
                    <td>{{ $user->profile ? $user->profile->employer : 'N/A' }}</td>
                    <td>{{ $user->profile ? $user->profile->category : 'N/A' }}</td>
                    <td>{{ $user->profile ? $user->profile->specialization : 'N/A' }}</td>
                    <td>
                        <!-- Add action buttons here, for example: -->
                        <a href="#" class="btn btn-info">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        

        <div class="card-footer">
            {{-- {{ $orders->links('vendor.pagination.simple-tailwind') }} --}}
        </div>
    </div>
@endsection
