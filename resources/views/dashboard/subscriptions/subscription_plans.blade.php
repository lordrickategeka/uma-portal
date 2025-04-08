@extends('layouts.dash')

@section('content')
    <h1 class="h3 mb-3"><strong>Subscriptions</strong></h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">All Subscriptions</h5>
        </div>
    </div>
    <div class="row">


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Full Member</h5>
                            <p>A fully registered medical practitioner who has met all financial obligations to the
                                Association as outlined in the Constitution.</p>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <!-- Set the color of thumbs-up to green when active plan exists -->
                                <i class="align-middle" data-feather="thumbs-up"
                                    style="color: {{ $activePlan ? 'green' : 'gray' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($plans as $plan)
                <div class="card">
                    <div class="card-body">
                        <!-- Display plan details dynamically -->
                        <div class="membership-category">
                            <h6 class="mt-3">{{ $plan->name }} ({{ ucfirst($plan->membershipCategory->name) }})</h6>
                            <ul>
                                <li><strong>Membership Fee:</strong> UGX {{ number_format($plan->price, 0) }} (one-time
                                    payment)</li>
                                <li><strong>Annual Subscription Fee:</strong> UGX {{ number_format($plan->price, 0) }}</li>
                            </ul>

                            @php
                                // Check if the user has an active plan
                                $userActivePlan = \DB::table('user_plans')
                                    ->where('user_id', auth()->id())
                                    ->where('plan_id', $plan->id)
                                    ->where('expires_at', '>', now()) // Plan is active if 'expires_at' is in the future
                                    ->first();
                            @endphp

                            @if ($userActivePlan)
                                <!-- If the user has an active plan, disable the Subscribe button -->
                                <button type="button" class="btn btn-secondary" disabled>Subscribed</button>
                            @else
                                <!-- If the user doesn't have an active plan, show Subscribe or Upgrade button -->
                                <form action="{{ route('plans.subscribe', $plan->id) }}" method="GET">
                                    @php
                                        // If the user has any other plan, show Upgrade button
                                        $userHasOtherPlans = \DB::table('user_plans')
                                            ->where('user_id', auth()->id())
                                            ->exists();
                                    @endphp

                                    @if ($userHasOtherPlans)
                                        <!-- If the user has other plans, show Upgrade button -->
                                        <form action="{{ route('plans.upgrade', $plan->id) }}" method="GET">
                                            <button type="submit" class="btn btn-warning">Upgrade</button>
                                        </form>
                                    @else
                                        <!-- Otherwise, show Subscribe button -->
                                        <button type="submit" class="btn btn-primary">Subscribe</button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
