@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>All Branches</h2>
    <a href="{{ route('branches.create') }}" class="btn btn-primary mb-3">Add New Branch</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Location</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
                <tr>
                    <td>{{ $branch->name }}</td>
                    <td>{{ $branch->code }}</td>
                    <td>{{ $branch->location }}</td>
                    <td>{{ $branch->email }}</td>
                    <td>{{ $branch->address }}</td>
                    <td>{{ $branch->phone_number }}</td>
                    <td>{{ $branch->manager_name }}</td>
                    <td>
                        <span class="badge bg-{{ $branch->status == 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($branch->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('branches.show', $branch) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection