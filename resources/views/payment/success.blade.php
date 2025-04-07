@extends('layouts.dash')

@section('content')
    <h1 class="h3 mb-3"><strong>Payment Successful for {{ $plan->name }}</strong></h1>
    <p>Your payment has been processed successfully.
        </br> Thank you for subscribing to the {{ $plan->name }} plan.</p>
    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Go Back to Subscriptions</a>
    <a href="#" class="btn btn-primary">Go order summary</a>
@endsection
