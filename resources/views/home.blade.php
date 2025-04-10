{{-- @extends('layouts.dash')
@section('content')
    <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

    <div class="row">
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Member</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="truck"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">2.382</h1>
                    <div class="mb-0">
                        <span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i> -3.65%</span>
                        <span class="text-muted">Since last week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Visitors</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">14.212</h1>
                    <div class="mb-0">
                        <span class="text-success"><i class="mdi mdi-arrow-bottom-right"></i> 5.25%</span>
                        <span class="text-muted">Since last week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Earnings</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">$21.300</h1>
                    <div class="mb-0">
                        <span class="text-success"><i class="mdi mdi-arrow-bottom-right"></i> 6.65%</span>
                        <span class="text-muted">Since last week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Orders</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">64</h1>
                    <div class="mb-0">
                        <span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i> -2.25%</span>
                        <span class="text-muted">Since last week</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">

                    <h5 class="card-title mb-0">Latest Projects</h5>
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="d-none d-xl-table-cell">Start Date</th>
                            <th class="d-none d-xl-table-cell">End Date</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Assignee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Project Apollo</td>
                            <td class="d-none d-xl-table-cell">01/01/2021</td>
                            <td class="d-none d-xl-table-cell">31/06/2021</td>
                            <td><span class="badge bg-success">Done</span></td>
                            <td class="d-none d-md-table-cell">Vanessa Tucker</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection --}}

@extends('layouts.dash')

@section('content')
    <h1 class="h3 mb-3"><strong>Payment Portal</strong> Dashboard</h1>

    <div class="row">
        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Active Members</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-1 mb-3">{{ $totalMembers }}</h4>
                    <div class="mb-0 text-muted">Updated in real time</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Pending Payments</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-1 mb-3">{{ $pendingPayments }}</h4>
                    <div class="mb-0 text-muted">Awaiting confirmation</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Payments</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-1 mb-3">UGX {{ number_format($totalEarnings) }}</h4>
                    <div class="mb-0 text-muted">All successful payments</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Invoices Issued</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info">
                                <i class="align-middle" data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-1 mb-3">{{ $totalInvoices }}</h4>
                    <div class="mb-0 text-muted">Total generated invoices</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Transactions --}}
    <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Transactions</h5>
                </div>
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th class="d-none d-xl-table-cell">Date</th>
                            <th class="d-none d-xl-table-cell">Amount</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td class="d-none d-xl-table-cell">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td class="d-none d-xl-table-cell">UGX {{ number_format($transaction->amount) }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status == 'successful' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">{{ ucfirst($transaction->method) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

