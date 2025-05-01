<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content {
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Subscription Confirmed</h1>
    </div>

    <div class="content">
        <p>Hello {{ $subscriber->name ?? 'there' }},</p>

        <p>Thank you for subscribing to our newsletter! You will now receive updates whenever we publish new content on
            our website - uma.ug.</p>

        <p>We're excited to keep you informed about our latest posts, events, and news.</p>

        <p>If you didn't subscribe to our newsletter, or if you change your mind, you can unsubscribe at any time by
            clicking the link below:</p>

        <div style="text-align: center;">
            <a href="{{ route('subscriber.unsubscribe', $subscriber->subscription_token) }}"
                class="button">Unsubscribe</a>
        </div>

        <p>Best regards,<br>The Team</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
        <p>This email was sent to {{ $subscriber->email }}</p>
    </div>
</body>

</html>
