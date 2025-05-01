{{-- resources/views/emails/payment_failed.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
</head>
<body>
    <h1>Payment Failed</h1>
    
    <p>Hello {{ $user->first_name }},</p>
    
    <p>We encountered an issue processing your payment for the {{ $plan->name }} plan.</p>
    
    <h2>Payment Details</h2>
    <ul>
        <li><strong>Plan:</strong> {{ $plan->name }}</li>
        <li><strong>Price:</strong> UGX {{ number_format($plan->price) }}</li>
        <li><strong>Failure Reason:</strong> {{ $failureReason }}</li>
    </ul>
    
    <h2>Next Steps</h2>
    <ol>
        <li>Please verify your payment method</li>
        <li>Check your account balance</li>
        <li>Retry the payment through your account dashboard</li>
    </ol>
    
    <p>If you continue to experience issues, please contact our support team.</p>
    
    <p>Best regards,<br>
    {{ config('app.name') }} Team</p>
</body>
</html>