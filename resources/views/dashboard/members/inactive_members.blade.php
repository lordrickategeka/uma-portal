@extends('layouts.dash')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Inactive Members</h4>
                        <div>
                            <span class="badge bg-info">Total: {{ $inactiveMembers->total() }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Search and Filter Form -->
                        <div class="mb-4">
                            <form action="{{ route('members.inactive') }}" method="GET" class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by name, email or UMA number" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4">
                                    <select name="status" class="form-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All
                                            Inactive</option>
                                        <option value="no_plan" {{ request('status') == 'no_plan' ? 'selected' : '' }}>No
                                            Plan</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                            Expired Plan</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>

                        <!-- Members Table -->
                        <div class="table-responsive">
                            <div class="card">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>UMA Number</th>
                                            <th>Category</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <th>Last Plan</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inactiveMembers as $user)
                                            <tr>
                                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                                <td>{{ $user->profile->uma_number ?? 'N/A' }}</td>
                                                <td>{{ $user->profile->category ?? 'N/A' }}</td>
                                                <td>{{ $user->profile->uma_branch ?? 'N/A' }}</td>
                                                <td>
                                                    @if (!$user->userPlan)
                                                        <span class="badge bg-secondary">No Plan</span>
                                                    @else
                                                        <span class="badge bg-danger">Expired
                                                            ({{ $user->days_since_expiration }} days ago)</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->userPlan)
                                                        <div>{{ $user->userPlan->plan->name ?? 'Unknown Plan' }}</div>
                                                        <small class="text-muted">
                                                            Expired:
                                                            {{ Carbon\Carbon::parse($user->userPlan->expires_at)->format('M d, Y') }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Never had a plan</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('members.show', $user->id) }}"
                                                        class="btn btn-sm btn-info">View</a>
                                                    <a href="#" class="btn btn-sm btn-success">Assign Plan</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $inactiveMembers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
