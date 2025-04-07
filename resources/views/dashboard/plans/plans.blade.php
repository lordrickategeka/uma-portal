@extends('layouts.dash')

@section('content')
<h1 class="h3 mb-3"><strong>Plans</strong></h1>

<a href="{{ route('plans.create') }}" class="btn btn-primary mb-3">Add New Plan</a>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">All Plans</h5>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Billing Cycle</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>
                        {{-- Check if the membershipCategory exists --}}
                        @if ($plan->membershipCategory)
                            {{ $plan->membershipCategory->name }}
                        @else
                            <span class="text-muted">Category Deleted</span>
                        @endif
                    </td>
                    <td>${{ number_format($plan->price, 2) }}</td>
                    <td>{{ ucfirst($plan->billing_cycle) }}</td>
                    <td>
                        <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($plan->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($plan->trashed())
                            <!-- If the plan is soft-deleted, show the restore button -->
                            <form action="{{ route('plans.restore', $plan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                            </form>
                        @else
                            <!-- Otherwise, show the edit and delete buttons -->
                            <a href="{{ route('plans.edit', $plan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No plans available.</td>
                </tr>
            @endforelse
        </tbody>
        
    </table>
    <div class="card-footer">
        {{ $plans->links() }}
    </div>
</div>
@endsection

