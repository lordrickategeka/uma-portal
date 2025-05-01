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
    <h1 class="h3 mb-3"><strong>All Transactions</strong></h1>

    <a href="#" class="btn btn-success mb-3">Export Data</a>

    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>Reference</th>
                    <th>Plan</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                <tr class="border-t">
                    <td><input type="checkbox" class="transaction-select" data-id="{{ $transaction->id }}"> {{ $index + 1 }}</td>
                    <td>{{ $transaction->reference }}</td>
                    <td>{{ $transaction->plan_name }}</td>
                    <td>{{ $transaction->name ?? $transaction->user_name }}</td>
                    <td>{{ $transaction->payment_method }}</td>
                    <td>{{ $transaction->amount }} {{ $transaction->currency }}</td>
                    <td>{{ $transaction->email }}</td>
                    <td>{{ $transaction->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $transaction->status == 'paid' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">View</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            {{ $transactions->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
    <script>
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.transaction-select');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection