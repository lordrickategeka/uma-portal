@extends('layouts.guest')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Payment Failed</h4>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fa fa-times-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h5 class="card-title text-center mb-4">We couldn't process your payment</h5>
                    
                    <div class="alert alert-secondary">
                        <h6>Order Details:</h6>
                        <p><strong>Order #:</strong> {{ $order->id }}</p>
                        <p><strong>Amount:</strong> {{ number_format($order->total_amount, 2) }} {{ $order->currency ?? 'UGX' }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <p class="text-center mb-4">
                        There was a problem processing your payment. This could be due to:
                    </p>
                    
                    <ul class="mb-4">
                        <li>Insufficient funds in your account</li>
                        <li>Incorrect payment details</li>
                        <li>Your card issuer declined the transaction</li>
                        <li>Technical issues with the payment gateway</li>
                    </ul>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary mr-2">
                            <i class="fa fa-eye"></i> View Order
                        </a>
                        <a href="{{route('subscriptions.index')}}" class="btn btn-primary">
                            <i class="fa fa-credit-card"></i> Try Again
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-center mb-0">
                        Need help? <a href="#">Contact our support team</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection