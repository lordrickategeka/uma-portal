@extends('layouts.dash')

@section('content')
    <div class="container d-flex flex-column flex-md-row justify-content-center align-items-start" style="min-height: 80vh;">
        <!-- Card for Member Information -->
        <div class="card w-100 w-md-50 mb-4 mb-md-0 me-md-3" style="position: sticky; top: 20px; z-index: 100;">
            <div class="card-body">
                <h1 class="h3 mb-3"><strong>Member Details</strong></h1>
                <hr />

                <!-- Basic Member Information -->
                <h5 class="mb-3"><strong>Personal Information</strong></h5>
                <div class="d-flex justify-content-center mb-3">
                    @if ($user->profile && $user->profile->photo)
                        <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="Profile Photo"
                            class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center"
                            style="width: 150px; height: 150px;">
                            <span class="text-white"
                                style="font-size: 3rem;">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->profile->phone ?? 'N/A' }}</p>
                <p><strong>Gender:</strong> {{ $user->profile->gender ?? 'N/A' }}</p>
                <p><strong>Age:</strong> {{ $user->profile->age ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $user->profile->address ?? 'N/A' }}</p>
                <hr />

                <!-- Subscription Information -->
                <h5 class="mb-3"><strong>Subscription Information</strong></h5>
                @if ($user->userPlan && $user->userPlan->plan)
                    <p><strong>Plan:</strong> {{ $user->userPlan->plan->name }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-success">Active</span>
                    </p>
                    <p><strong>Subscribed On:</strong>
                        {{ $user->userPlan->subscribed_at ? date('M d, Y', strtotime($user->userPlan->subscribed_at)) : 'N/A' }}
                    </p>
                    <p><strong>Expires On:</strong>
                        @if ($user->userPlan->plan->name == 'Life Member')
                            <span class="badge bg-primary">Lifetime</span>
                        @else
                            {{ $user->userPlan->expires_at ? date('M d, Y', strtotime($user->userPlan->expires_at)) : 'Dec 31, ' . date('Y') }}
                        @endif
                    </p>
                    @if ($user->profile && $user->profile->membership_category_id)
                        <p><strong>Membership Category:</strong> {{ $user->profile->membershipCategory->name ?? 'N/A' }}
                        </p>
                    @endif
                @else
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-danger"> No Active Subscription Plan </a>
                @endif
                <hr />

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="{{ route('members.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a>
                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Delete Member
                        </button>
                        <a href="{{ route('members.active') }}" class="btn btn-secondary">Back to List</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card for Additional Information -->
        <div class="card w-100 w-md-50">
            <div class="card-body">
                <h5 class="mb-3"><strong>Emergency Contact</strong></h5>
                <p><strong>Next of Kin:</strong> {{ $user->profile->next_of_kin ?? 'N/A' }}</p>
                <p><strong>Next of Kin Phone:</strong> {{ $user->profile->next_of_kin_phone ?? 'N/A' }}</p>
                <hr />

                <h5 class="mb-3"><strong>Referee Information</strong></h5>
                <p><strong>Referee:</strong> {{ $user->profile->referee ?? 'N/A' }}</p>
                <p><strong>Referee Phone 1:</strong> {{ $user->profile->referee_phone1 ?? 'N/A' }}</p>
                <p><strong>Referee Phone 2:</strong> {{ $user->profile->referee_phone2 ?? 'N/A' }}</p>
                <hr />

                <!-- Professional Information -->
                <h5 class="mb-3"><strong>Professional Details</strong></h5>
                <p><strong>Employer:</strong> {{ $user->profile->employer ?? 'N/A' }}</p>
                <p><strong>Category:</strong> {{ $user->profile->category ?? 'N/A' }}</p>
                <p><strong>Specialization:</strong> {{ $user->profile->specialization ?? 'N/A' }}</p>
                <p><strong>UMDPC Number:</strong> {{ $user->profile->umdpc_number ?? 'N/A' }}</p>
                <p><strong>UMA Number:</strong> {{ $user->profile->uma_number ?? 'N/A' }}</p>
                <p><strong>Branch:</strong> {{ $user->profile->uma_branch ?? 'N/A' }}</p>
                <hr />

                <h5 class="mb-3"><strong>Documents</strong></h5>
                <div class="list-group">
                    @if ($user->profile && $user->profile->national_id)
                        <a href="{{ asset('storage/' . $user->profile->national_id) }}"
                            class="list-group-item list-group-item-action" target="_blank">
                            <i class="fa fa-id-card me-2"></i> National ID
                        </a>
                    @else
                        <span class="list-group-item list-group-item-action disabled">
                            <i class="fa fa-id-card me-2"></i> National ID (Not uploaded)
                        </span>
                    @endif

                    @if ($user->profile && $user->profile->license_document)
                        <a href="{{ asset('storage/' . $user->profile->license_document) }}"
                            class="list-group-item list-group-item-action" target="_blank">
                            <i class="fa fa-file-medical me-2"></i> License Document
                        </a>
                    @else
                        <span class="list-group-item list-group-item-action disabled">
                            <i class="fa fa-file-medical me-2"></i> License Document (Not uploaded)
                        </span>
                    @endif

                    @if ($user->profile && $user->profile->signature)
                        <a href="{{ asset('storage/' . $user->profile->signature) }}"
                            class="list-group-item list-group-item-action" target="_blank">
                            <i class="fa fa-signature me-2"></i> Signature
                        </a>
                    @else
                        <span class="list-group-item list-group-item-action disabled">
                            <i class="fa fa-signature me-2"></i> Signature (Not uploaded)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        
    </div>

    <div class="card w-100 w-md-50">
        <div class="card-body">
            <h5 class="mb-3"><strong>Payment History</strong></h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders()->latest()->take(5)->get() as $order)
                            <tr>
                                <td>{{ date('M d, Y', strtotime($order->created_at)) }}</td>
                                <td>{{ $order->plan->name ?? 'N/A' }}</td>
                                <td>UGX {{ number_format($order->total_amount, 0) }}</td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No payment records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <a href="{{ route('members.payments', $user->id) }}" class="btn btn-sm btn-info">View All Payments</a>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this member? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('members.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Member</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
