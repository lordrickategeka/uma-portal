<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h3 class="mb-0">Payment Cancelled</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Your payment was cancelled</h4>
                        <p>You cancelled the payment process for the following transaction:</p>
                    </div>

                    <div class="mb-4">
                        <h5>Transaction Details</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Reference
                                <span class="badge bg-primary">{{ $transaction->reference }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Amount
                                <span>UGX {{ number_format($transaction->amount, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Plan
                                <span>{{ $plan->name }}</span>
                            </li>
                            @if($installmentPlan)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Installment
                                    <span>{{ $transaction->installment_number }} of {{ $installmentPlan->total_installments }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="text-center">
                        <p>You can try again or choose another payment method.</p>
                        <a href="{{ route('payments.retry', ['transaction_id' => $transaction->id]) }}" class="btn btn-primary">
                            Try Payment Again
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>