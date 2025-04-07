@extends('layouts.guest')

@section('content')
<div class="container">
    <h2>Proceed with Payment</h2>
    <p>Your order ID: {{ $order->id }} is ready for payment. Please proceed with the payment below.</p>
    <iframe src="{{ $paymentUrl }}" width="100%" height="600px" frameborder="0">
        Your browser does not support iframes.
    </iframe>
</div>
@endsection