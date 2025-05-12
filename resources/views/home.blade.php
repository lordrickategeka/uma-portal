@extends('layouts.dash')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0"><strong>Portal</strong> Dashboard</h1>

        @if ($hasActivePlan)
            <div class="bg-success text-white px-4 py-2 rounded" style="min-width: 200px; text-align: center; ">
                Membership status: Active
            </div>
        @else
            <div class="text-white px-4 py-2 rounded" style="min-width: 200px; text-align: center; background: #B31312">
                Membership status: Inactive
            </div>
        @endif
    </div>

    @hasanyrole('super-admin|admin|member')
        <div class="row">
            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" 
                style=" background: #FE9F43; border-radius: 1rem;">
                    <div class="card-body dash-cards">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Total Members</h5>
                            <div class="stat text-info">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-info mb-2">{{ number_format($totalMembers) }}</h4>
                        <small class="text-white">Registered users</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #092C4C; border-radius: 1rem;">
                    <div class="card-body dash-cards">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Pending Payments</h5>
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-warning mb-2">{{ number_format($pendingPayments) }}</h4>
                        <small class="text-white">Awaiting confirmation</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #0E9384; border-radius: 1rem;">
                    <div class="card-body dash-cards">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Total Payments</h5>
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-success mb-2">UGX {{ number_format($totalPaidAmount, 2) }}</h4>
                        <small class="text-white">All successful payments</small>
                    </div>
                </div>
            </div>


            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #155EEF; border-radius: 1rem;">
                    <div class="card-body dash-cards">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Total Invoices</h5>
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="file-text"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-primary mb-2">{{ number_format($totalInvoices) }}</h4>
                        <small class="text-white">Completed transactions</small>
                    </div>
                </div>
            </div>

        </div>
    @endhasanyrole

    @hasanyrole('super-admin|editor')
        <div class="row mt-2">
            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #DC3545; border-radius: 1rem;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Total Posts</h5>
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="edit-3"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-primary mb-2">{{ number_format($totalPosts) }}</h4>
                        <small class="text-white">All blog posts</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #43feee; border-radius: 1rem;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Pending Posts</h5>
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-warning mb-2">{{ number_format($pendingPosts) }}</h4>
                        <small class="text-white">Awaiting review</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #c0fe43; border-radius: 1rem;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Upcoming Events</h5>
                            <div class="stat text-info">
                                <i class="align-middle" data-feather="calendar"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-info mb-2">{{ number_format($upcomingEvents) }}</h4>
                        <small class="text-white">Future scheduled events</small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card shadow-sm border-0" style=" background: #FE9F43; border-radius: 1rem;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Publications</h5>
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="book-open"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-success mb-2">{{ number_format($publishedPublications) }}</h4>
                        <small class="text-white">Published publications</small>
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole

    {{-- Latest Transactions --}}
    @hasanyrole('super-admin|admin|member')
        <div class="row">
            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Latest Transactions</h5>
                    </div>
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"> No.</th>
                                <th>Transaction ID</th>
                                <th class="d-none d-xl-table-cell">Amount</th>
                                <th class="d-none d-xl-table-cell">Payment Date</th>
                                <th class="d-none d-xl-table-cell">Expires Date</th>
                                <th class="d-none d-md-table-cell">Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $index => $transaction)
                                <tr>
                                    <td><input type="checkbox" class="order-select" data-id="{{ $transaction->id }}">
                                        {{ $index + 1 }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $transaction->reference_number }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $transaction->total_amount }}
                                        {{ $transaction->currency }}</td>

                                    <td>
                                        {{ optional($transaction->user->userPlan)->subscribed_at
                                            ? \Carbon\Carbon::parse($transaction->user->userPlan->subscribed_at)->format('d M Y')
                                            : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ optional($transaction->user->userPlan)->expires_at
                                            ? \Carbon\Carbon::parse($transaction->user->userPlan->expires_at)->format('d M Y')
                                            : 'N/A' }}
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $transaction->user->defaultPaymentMethod->paymentMethod->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->payment_status == 'paid' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-white">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endhasanyrole
@endsection
