@extends('layouts.dash')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between mb-3">
            <h1 class="h3"><strong>Transaction Details</strong></h1>
            <div>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Transactions
                </a>
                @if($transaction->status == 'pending' && Auth::user()->can('approve-transactions'))
                    <a href="{{ route('transactions.approve', $transaction->id) }}" class="btn btn-success ms-2">
                        <i class="fas fa-check me-1"></i> Approve Transaction
                    </a>
                @endif
                @if(Auth::user()->can('edit-transactions'))
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-primary ms-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Transaction Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Transaction Information</h5>
                        <span class="badge {{ $transaction->status == 'paid' ? 'bg-success' : ($transaction->status == 'pending' ? 'bg-warning' : 'bg-danger') }} p-2">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Transaction ID:</th>
                                        <td>{{ $transaction->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number:</th>
                                        <td>{{ $transaction->reference ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td><strong>{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method:</th>
                                        <td>{{ $transaction->payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td>{{ $transaction->description ?? 'No description' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction Date:</th>
                                        <td>{{ $transaction->created_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                    @if($transaction->payment_date)
                                    <tr>
                                        <th>Payment Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($transaction->payment_date)->format('F d, Y h:i A') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Notes:</th>
                                        <td>{{ $transaction->notes ?? 'No notes' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Plan Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Plan Information</h5>
                    </div>
                    <div class="card-body">
                        @if($transaction->plan)
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="w-25">Plan Name:</th>
                                            <td>{{ $transaction->plan->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Plan Price:</th>
                                            <td>{{ number_format($transaction->plan->price, 2) }} {{ $transaction->currency }}</td>
                                        </tr>
                                        <tr>
                                            <th>Duration:</th>
                                            <td>{{ $transaction->plan->duration }} {{ Str::plural('day', $transaction->plan->duration) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $transaction->plan->description ?? 'No description' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> No plan information available for this transaction.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Member Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Member Information</h5>
                    </div>
                    <div class="card-body">
                        @if($transaction->user)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-lg me-3">
                                    @if($transaction->user->profile && $transaction->user->profile->photo)
                                        <img src="{{ asset('storage/' . $transaction->user->profile->photo) }}" alt="{{ $transaction->user->first_name }}" class="rounded-circle">
                                    @else
                                        <div class="avatar-placeholder bg-primary text-white">
                                            {{ strtoupper(substr($transaction->user->first_name, 0, 1)) }}{{ strtoupper(substr($transaction->user->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $transaction->user->first_name }} {{ $transaction->user->last_name }}</h5>
                                    @if($transaction->user->profile && $transaction->user->profile->uma_number)
                                        <span class="badge bg-info">{{ $transaction->user->profile->uma_number }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="w-25">Email:</th>
                                            <td>{{ $transaction->user->email }}</td>
                                        </tr>
                                        @if($transaction->user->profile)
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $transaction->user->profile->phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Category:</th>
                                                <td>{{ $transaction->user->profile->category ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Branch:</th>
                                                <td>{{ $transaction->user->profile->uma_branch ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>UMA Number:</th>
                                                <td>{{ $transaction->user->profile->uma_number ?? 'N/A' }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('members.show', $transaction->user_id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-user me-1"></i> View Member Profile
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> No member information available.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Subscription Status Card -->
                @if($transaction->userPlan)
                <div class="card mb-4">
                    <div class="card-header bg-{{ $transaction->userPlan->status == 'active' ? 'success' : 'danger' }} text-white">
                        <h5 class="mb-0">Subscription Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $transaction->userPlan->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->userPlan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Start Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($transaction->userPlan->subscribed_at)->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($transaction->userPlan->expires_at)->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remaining:</th>
                                        <td>
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $expiryDate = \Carbon\Carbon::parse($transaction->userPlan->expires_at);
                                                $daysLeft = $now->diffInDays($expiryDate, false);
                                            @endphp
                                            
                                            @if($daysLeft > 0)
                                                <span class="text-success">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left</span>
                                            @else
                                                <span class="text-danger">Expired {{ abs($daysLeft) }} {{ Str::plural('day', abs($daysLeft)) }} ago</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin Actions Card -->
                @if(Auth::user()->can('manage-transactions'))
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Admin Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($transaction->status == 'pending')
                                <a href="{{ route('transactions.approve', $transaction->id) }}" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Approve Transaction
                                </a>
                            @endif
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Transaction
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Delete Transaction
                            </button>
                            @if($transaction->status == 'paid' && $transaction->userPlan)
                                <a href="{{ route('user-plans.edit', $transaction->userPlan->id) }}" class="btn btn-info">
                                    <i class="fas fa-calendar-alt me-1"></i> Update Subscription
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this transaction? This action cannot be undone.</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> This may affect the member's subscription status.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar {
            width: 60px;
            height: 60px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
            border-radius: 50%;
        }
    </style>
@endsection