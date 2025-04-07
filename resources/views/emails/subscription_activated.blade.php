{{-- resources/views/emails/subscription_activated.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Subscription Activated</title>
</head>
<body>
    <h1>Subscription Activated</h1>
    
    <p>Hello {{ $user->first_name }},</p>
    
    <p>Your subscription has been successfully activated!</p>
    
    <h2>Subscription Details</h2>
    <ul>
        <li><strong>Plan:</strong> {{ $plan->name }}</li>
        <li><strong>Price:</strong> UGX {{ number_format($plan->price) }}</li>
        <li><strong>Payment Method:</strong> {{ $paymentMethod }}</li>
        <li><strong>Confirmation Code:</strong> {{ $confirmationCode }}</li>
    </ul>
    
    <h2>Subscription Period</h2>
    <ul>
        <li><strong>Start Date:</strong> {{ $subscriptionStart }}</li>
        <li><strong>Expiry Date:</strong> {{ $subscriptionExpiry }}</li>
    </ul>
    
    <p>Thank you for your subscription. If you have any questions, please contact our support team.</p>
    
    <p>Best regards,<br>
    {{ config('app.name') }} Team</p>
</body>
</html>