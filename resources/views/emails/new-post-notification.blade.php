<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Blog Post: {{ $blog->title }}</title>
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
        .post-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .unsubscribe {
            display: inline-block;
            color: #6c757d;
            text-decoration: none;
            margin-top: 20px;
            font-size: 12px;
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
        <h1>New Blog Post</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $subscriber->name ?? 'there' }},</p>
        
        <p>We've just published a new blog post that we thought you might enjoy:</p>
        
        <h2>{{ $blog->title }}</h2>
        
        @if($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="post-image">
        @endif
        
        <p>{{ $excerpt }}</p>
        
        <div style="text-align: center;">
            <a href="{{ url('blog/' . $blog->slug) }}" class="button">Read Full Post</a>
        </div>
        
        <p>We hope you enjoy reading it!</p>
        
        <p>Best regards,<br>The Team</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
        <p>
            This email was sent to {{ $subscriber->email }} because you subscribed to our newsletter.
            <a href="{{ route('subscription.unsubscribe', $subscriber->subscription_token) }}" class="unsubscribe">Unsubscribe</a>
        </p>
    </div>
</body>
</html>