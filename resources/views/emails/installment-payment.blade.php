<!-- resources/views/emails/installment-payment.blade.php -->
@component('mail::message')
# Installment Payment Confirmation

Dear {{ $user->first_name }},

Thank you for your payment. We are pleased to confirm that your installment payment for {{ $plan->name }} has been received.

**Payment Details:**
- **Reference:** {{ $reference }}
- **Amount:** UGX {{ number_format($installmentPlan->amount_per_installment, 0) }}
- **Payment Method:** {{ $paymentMethod }}
- **Installment:** {{ $installmentNumber }} of {{ $installmentPlan->total_installments }}

**Installment Progress:**
{{ $installmentPlan->paid_installments }} of {{ $installmentPlan->total_installments }} installments completed

@if($installmentPlan->paid_installments < $installmentPlan->total_installments)
Your next installment of UGX {{ number_format($installmentPlan->amount_per_installment, 0) }} is due on {{ $installmentPlan->next_payment_date->format('d M Y') }}.

You can make your next payment by visiting your account dashboard.
@else
All installments have been paid. Your subscription is now active.
@endif

Thank you for your membership!

Regards,<br>
{{ config('app.name') }}
@endcomponent