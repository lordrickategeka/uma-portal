@extends('layouts.dash')

@section('content')
    <h1 class="h3 mb-3"><strong>Subscriptions</strong></h1>

    <div class="card" >
        <div class="card-header" style="background: #003092;">
            <h5 class="card-title" style="color: #f9fafd;">All Subscriptions</h5>
        </div>
    </div>
    <div class="row">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="col-12 col-sm-6 col-md-6">
            @php
                $hasAnyActivePlan = $userActivePlans->isNotEmpty();
            @endphp

            @foreach ($plans as $plan)
                @php
                    $userActivePlan = $userActivePlans->get($plan->id);
                    $isLifeMembership =
                        strtolower($plan->membershipCategory->name) === 'life' ||
                        strtolower($plan->name) === 'life membership';
                    $ongoingInstallment = $installmentPlans->get($plan->id);
                    $hasCompletedInstallment = $ongoingInstallment && $ongoingInstallment->status === 'completed';
                    $pendingTransaction =
                        $ongoingInstallment && !$hasCompletedInstallment
                            ? $pendingTransactions->get($ongoingInstallment->id)
                            : null;
                @endphp

                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mt-3">{{ $plan->name }} ({{ ucfirst($plan->membershipCategory->name) }})</h6>
                        <p class="mt-3">{{ $plan->description ?? $plan->membershipCategory->description }}</p>
                        <ul>
                            <li><strong>Membership Fee:</strong> UGX {{ number_format($plan->price, 0) }}</li>
                            @unless ($isLifeMembership)
                                <li><strong>Annual Subscription Fee:</strong> UGX {{ number_format($plan->price, 0) }}</li>
                            @endunless
                        </ul>

                        @if ($userActivePlan || $hasCompletedInstallment)
                            <button type="button" class="btn btn-success" disabled>
                                <i class="fas fa-check-circle me-1"></i> Subscribed
                            </button>
                            <div class="mt-2">
                                <small>
                                    @if ($isLifeMembership)
                                        Life Membership (Non-expiring)
                                    @else
                                        Expires:
                                        {{ \Carbon\Carbon::parse($userActivePlan->expires_at ??DB::table('user_plans')->where('user_id', auth()->id())->where('plan_id', $plan->id)->value('expires_at'))->format('d M Y') }}
                                    @endif
                                </small>
                            </div>
                        @elseif (($hasAnyActivePlan || $hasAnyOngoingInstallment) && !$ongoingInstallment)
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i> Unavailable
                            </button>
                            <small class="d-block mt-1">
                                You already have an active membership plan or ongoing installment.
                            </small>
                        @elseif ($ongoingInstallment && !$hasCompletedInstallment)
                            <div class="installment-info">
                                <div class="mb-2">
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $progress =
                                                ($ongoingInstallment->paid_installments /
                                                    $ongoingInstallment->total_installments) *
                                                100;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;"
                                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ round($progress) }}%
                                        </div>
                                    </div>
                                </div>

                                <p><strong>Installment Plan:</strong>
                                    {{ $ongoingInstallment->paid_installments }}/{{ $ongoingInstallment->total_installments }}
                                    paid
                                    (Remaining: UGX {{ number_format($ongoingInstallment->remaining_amount, 0) }})
                                </p>

                                @if ($pendingTransaction)
                                    <a href="{{ route('payments.continue', $pendingTransaction->id) }}"
                                        class="btn btn-warning">
                                        <i class="fas fa-sync-alt me-1"></i> Continue Payment
                                    </a>
                                    <small class="d-block mt-1">
                                        You have a pending payment started
                                        {{ \Carbon\Carbon::parse($pendingTransaction->created_at)->diffForHumans() }}.
                                    </small>
                                @else
                                    <a href="{{ route('installment.make-next-payment', $ongoingInstallment->id) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-credit-card me-1"></i> Pay Next Installment
                                    </a>
                                @endif

                                <div class="mt-2">
                                    <small>
                                        Next payment: UGX
                                        {{ number_format($ongoingInstallment->amount_per_installment, 0) }} due by
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
                            @if ($isLifeMembership)
                                <div class="payment-options mt-3">
                                    <h6>Payment Options:</h6>
                                    <form action="{{ route('payments.initialize') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                        <select name="installment_option" class="form-control mb-3">
                                            <option value="full">Full Payment - UGX {{ number_format($plan->price, 0) }}
                                            </option>
                                            @for ($i = 2; $i <= 4; $i++)
                                                <option value="{{ $i }}">{{ $i }} Installments - UGX
                                                    {{ number_format($plan->price / $i, 0) }} each</option>
                                            @endfor
                                        </select>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-credit-card me-1"></i> Proceed to Payment
                                        </button>
                                    </form>
                                </div>
                            @else
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
            @endforeach
        </div>

        <div class="col-12 col-sm-6 col-md-6">
            <div class="card h-auto" style="position: sticky; top: 100px; z-index: 100; background: aliceblue;">
                <div class="card-body">
                    <h1 class="h4 mb-3"><strong>Membership Benefits</strong></h1>
                    <p>UMA offers a wide range of benefits to its members, including:</p>
                    <ol class="ps-3">
                        <li><strong>Continuous Professional Development (CPD) Programs</strong> – Gain access to structured
                            learning and training opportunities.</li><br />
                        <li><strong>Representation on statutory and professional bodies</strong> – Ensure your voice is
                            heard in
                            key policy and regulatory discussions.</li><br />
                        <li><strong>Access to financial support through UMA SACCO</strong> – Benefit from affordable
                            financial
                            facilities tailored for medical professionals.</li><br />
                        <li><strong>Group Indemnity Insurance</strong> – Secure professional liability coverage for medical
                            practitioners.</li><br />
                        <li><strong>Labour Relations Support</strong> – Receive expert guidance on employment matters in
                            both
                            public and private healthcare sectors.</li><br />
                        <li><strong>Legal Aid Clinic for Doctors</strong> – Access legal support and advisory services
                            tailored
                            to the medical profession.</li><br />
                        <li><strong>Investment Opportunities</strong> – Participate in joint investment initiatives designed
                            for
                            doctors.</li><br />
                        <li><strong>Exclusive Access to Medical Publications</strong> – Enjoy free access to the Uganda
                            Medical
                            Journal and the UMA Newsletter.</li><br />
                        <li><strong>Research Publication Platform</strong> – Publish approved research articles at no cost.
                        </li>
                    </ol>
                </div>
            </div>
        </div>

    @endsection
