@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Payments</h2>
    @if ($payments->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tx Ref</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Order ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->tx_ref }}</td>
                        <td>{{ number_format($payment->amount) }} {{ $payment->currency }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'successful' ? 'success' : 'danger' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->customer_phone }}</td>
                        <td>{{ $payment->customer_email }}</td>
                        <td>{{ $payment->order_id }}</td>
                        <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $payments->links() }}
    @else
        <p>No payments found.</p>
    @endif
</div>
@endsection
