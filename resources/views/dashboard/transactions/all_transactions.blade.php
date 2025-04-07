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
                    <th>Order ID</th>
                    <th>Subscription</th>
                    <th>Member</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Expires Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td><input type="checkbox" class="order-select" data-id="{{ $order->id }}"> {{ $index + 1 }}</td>
                    <td>{{ $order->reference_number }}</td>
                    <td>{{ $order->plan_name }}</td>
                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                    <td>{{ $order->payment_method_name }}</td>
                    <td>{{ $order->total_amount }} {{ $order->currency }}</td>
                    
                    <td>{{ $order->subscribed_at ? \Carbon\Carbon::parse($order->subscribed_at)->format('d M Y') : 'N/A' }}</td>
                    <td>{{ $order->expires_at ? \Carbon\Carbon::parse($order->expires_at)->format('d M Y') : 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                           
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            {{ $orders->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
    <script>
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.order-select');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
