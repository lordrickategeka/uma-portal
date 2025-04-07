@extends('layouts.guest')

@section('content')
<form action="{{ route('payment.subscription') }}" method="POST">
    @csrf
    @method('post')
    {{-- <input type="hidden" name="plan_id" value="{{ $plan->id }}"> --}}
    <button type="submit" class="btn btn-success">Proceed to Payment</button>
</form>

<script>
    document.getElementById('paymentForm').submit();
</script>
@endsection
