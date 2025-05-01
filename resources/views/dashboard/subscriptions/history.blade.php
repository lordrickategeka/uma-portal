<!-- resources/views/installments/history.blade.php -->
@extends('layouts.dash')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Installment Payment History</div>

                <div class="card-body">
                    @if($installmentPlans->isEmpty())
                        <p>You have no installment plans.</p>
                    @else
                        @foreach($installmentPlans as $plan)
                            <div class="installment-plan mb-4 p-3 border rounded">
                                <h5>{{ $plan->plan->name }} - {{ ucfirst($plan->status) }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Total Amount:</strong> UGX {{ number_format($plan->total_amount, 0) }}</p>
                                        <p><strong>Installment Amount:</strong> UGX {{ number_format($plan->amount_per_installment, 0) }}</p>
                                        <p><strong>Progress:</strong> {{ $plan->paid_installments }} of {{ $plan->total_installments }} installments paid</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Remaining Amount:</strong> UGX {{ number_format($plan->remaining_amount, 0) }}</p>
                                        @if($plan->status != 'completed')
                                            <p><strong>Next Payment Due:</strong> {{ $plan->next_payment_date->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <h6 class="mt-3">Payment History</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $transactions = \App\Models\Transaction::where('installment_plan_id', $plan->id)
                                                ->orderBy('installment_number')
                                                ->get();
                                        @endphp
                                        
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>Installment {{ $transaction->installment_number }}</td>
                                                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                                <td>UGX {{ number_format($transaction->amount, 0) }}</td>
                                                <td>
                                                    @if($transaction->status == 'completed')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($transaction->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Failed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                        @for($i = $transactions->count() + 1; $i <= $plan->total_installments; $i++)
                                            <tr>
                                                <td>Installment {{ $i }}</td>
                                                <td>Upcoming</td>
                                                <td>UGX {{ number_format($plan->amount_per_installment, 0) }}</td>
                                                <td><span class="badge bg-secondary">Not Due</span></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                                
                                @if($plan->status != 'completed' && $plan->paid_installments < $plan->total_installments)
                                    <div class="text-end">
                                        <a href="{{ route('installment.make-next-payment', $plan->id) }}" class="btn btn-primary">
                                            Make Next Payment
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection