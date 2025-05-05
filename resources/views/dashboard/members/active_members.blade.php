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
    <h1 class="h3 mb-3"><strong>Active Subscribers</strong></h1>

    <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Add New Member</a>

    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subscription Plan</th>
                    <th>Start Date</th>
                    <th>Expiry Date</th>
                    <th>Specialization</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allActiveMembers as $index => $user)
                <tr>
                    <td>
                        <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}" class="subscriber-select">
                        {{ $index + 1 }}
                    </td>
                    <td>{{ $user->first_name }}
                        <small>{{ $user->last_name }}</small>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profile ? $user->profile->phone : 'N/A' }}</td>
                    <td>
                        @if($user->userPlan && $user->userPlan->plan)
                            {{ $user->userPlan->plan->name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->userPlan)
                            {{ $user->userPlan->subscribed_at ? date('M d, Y', strtotime($user->userPlan->subscribed_at)) : 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->userPlan)
                            @if($user->userPlan->plan && $user->userPlan->plan->name == 'Life Member')
                                Lifetime
                            @else
                                {{ $user->userPlan->expires_at ? date('M d, Y', strtotime($user->userPlan->expires_at)) : 'Dec 31, '.date('Y') }}
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $user->profile ? $user->profile->specialization : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('members.show', $user->id) }}" class="btn btn-sm btn-info">View</a>
                        <!-- Add more action buttons as needed -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="card-footer">
            <!-- Pagination would go here if needed -->
            
        </div>
    </div>
@endsection