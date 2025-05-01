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
            @php
                // Check if user has ANY active plan
                $hasAnyActivePlan = $userActivePlans->isNotEmpty();
            @endphp

            @foreach ($plans as $plan)
                <div class="card">
                    <div class="card-body">
                        <!-- Display plan details dynamically -->
                        <div class="membership-category">
                            <h6 class="mt-3">{{ $plan->name }} ({{ ucfirst($plan->membershipCategory->name) }})</h6>
                            <p class="mt-3">{{ $plan->description ?? $plan->membershipCategory->description }}</p>
                            <ul>
                                <li><strong>Membership Fee:</strong> UGX {{ number_format($plan->price, 0) }}</li>
                                @if($plan->membershipCategory->name !== 'life')
                                    <li><strong>Annual Subscription Fee:</strong> UGX {{ number_format($plan->price, 0) }}</li>
                                @endif
                            </ul>

                            @php
                                // Check if the user has this specific active plan
                                $userActivePlan = $userActivePlans->get($plan->id);

                                // Check if this is a life membership plan
                                $isLifeMembership =
                                    $plan->membershipCategory->name === 'life' ||
                                    strtolower($plan->name) === 'life membership';

                                // Check if user has an ongoing installment plan for THIS SPECIFIC plan
                                $ongoingInstallment = $installmentPlans->get($plan->id);

                                // Check if installment is completed
                                $hasCompletedInstallment = $ongoingInstallment && $ongoingInstallment->status === 'completed';

                                // Get any incomplete transaction for this plan's installment
                                $pendingTransaction = $ongoingInstallment && $ongoingInstallment->status !== 'completed' ? 
                                    $pendingTransactions->get($ongoingInstallment->id) : null;
                            @endphp

                            @if ($userActivePlan || $hasCompletedInstallment)
                                <!-- If the user has this active plan or completed installment payments -->
                                <button type="button" class="btn btn-success" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Subscribed
                                </button>
                                <div class="mt-2">
                                    <small>
                                        @if($isLifeMembership)
                                            Life Membership (Non-expiring)
                                        @else
                                            Expires: {{ \Carbon\Carbon::parse($userActivePlan->expires_at ?? ($hasCompletedInstallment ? DB::table('user_plans')->where('user_id', auth()->id())->where('plan_id', $plan->id)->first()->expires_at : null))->format('d M Y') }}
                                        @endif
                                    </small>
                                </div>
                            @elseif (($hasAnyActivePlan || $hasAnyOngoingInstallment) && !$ongoingInstallment)
                                <!-- If the user has another active plan or ongoing installment (not this one) -->
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-lock me-1"></i> Unavailable
                                </button>
                                <small class="d-block mt-1">
                                    You already have an active membership plan or ongoing installment.
                                </small>
                            @elseif ($ongoingInstallment && $ongoingInstallment->status !== 'completed')
                                <!-- If the user has an ongoing (non-completed) installment plan for THIS PLAN -->
                                <div class="installment-info">
                                    <div class="mb-2">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($ongoingInstallment->paid_installments / $ongoingInstallment->total_installments) * 100 }}%;"
                                                aria-valuenow="{{ ($ongoingInstallment->paid_installments / $ongoingInstallment->total_installments) * 100 }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ round(($ongoingInstallment->paid_installments / $ongoingInstallment->total_installments) * 100) }}%
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-2">
                                        <strong>Installment Plan:</strong>
                                        {{ $ongoingInstallment->paid_installments }}/{{ $ongoingInstallment->total_installments }}
                                        paid
                                        (Remaining: UGX {{ number_format($ongoingInstallment->remaining_amount, 0) }})
                                    </p>

                                    @if ($pendingTransaction)
                                        <!-- If there's a pending transaction, show Continue Payment button -->
                                        <a href="{{ route('payments.continue', $pendingTransaction->id) }}"
                                            class="btn btn-warning">
                                            <i class="fas fa-sync-alt me-1"></i> Continue Payment
                                        </a>
                                        <small class="d-block mt-1">
                                            You have a pending payment started {{ \Carbon\Carbon::parse($pendingTransaction->created_at)->diffForHumans() }}.
                                            Click to continue where you left off.
                                        </small>
                                    @else
                                        <!-- No pending transaction, show Make Next Payment button -->
                                        <a href="{{ route('installment.make-next-payment', $ongoingInstallment->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-credit-card me-1"></i> Pay Next Installment
                                        </a>
                                    @endif

                                    <div class="mt-2">
                                        <small>
                                            Next payment: UGX
                                            {{ number_format($ongoingInstallment->amount_per_installment, 0) }}
                                            due by
                                            {{ \Carbon\Carbon::parse($ongoingInstallment->next_payment_date)->format('d M Y') }}
                                        </small>
                                    </div>

                                    <div class="mt-2">
                                        <a href="{{ route('installments.index', $ongoingInstallment->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-info-circle me-1"></i> View Installment Details
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- If the user doesn't have an active plan or ongoing installment -->
                                @if ($isLifeMembership)
                                    <div class="payment-options mt-3">
                                        <h6>Payment Options:</h6>
                                        <form action="{{ route('payments.initialize') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                            <div class="form-group">
                                                <select name="installment_option" class="form-control mb-3">
                                                    <option value="full">Full Payment - UGX
                                                        {{ number_format($plan->price, 0) }}</option>
                                                    <option value="2">2 Installments - UGX
                                                        {{ number_format($plan->price / 2, 0) }} each</option>
                                                    <option value="3">3 Installments - UGX
                                                        {{ number_format($plan->price / 3, 0) }} each</option>
                                                    <option value="4">4 Installments - UGX
                                                        {{ number_format($plan->price / 4, 0) }} each</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-credit-card me-1"></i> Proceed to Payment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <!-- Regular subscription button for non-life memberships -->
                                    <form action="{{ route('payments.initialize') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-credit-card me-1"></i> Subscribe
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection