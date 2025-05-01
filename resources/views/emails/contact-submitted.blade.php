<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
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
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
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
        <h1>New Contact Form Submission</h1>
    </div>
    
    <div class="content">
        <p>A new contact form submission has been received.</p>
        
        <div class="details">
            <h3>Contact Details:</h3>
            <p><strong>Date:</strong> {{ $contactEntry->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Name:</strong> {{ $contactEntry->name }}</p>
            <p><strong>Email:</strong> {{ $contactEntry->email }}</p>
            <p><strong>Phone:</strong> {{ $contactEntry->phone }}</p>
            <p><strong>Subject:</strong> {{ $contactEntry->subject }}</p>
            
            <h3>Message:</h3>
            <p>{{ $contactEntry->message }}</p>
        </div>
        
        <p>You can also view this submission in the admin dashboard.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification.</p>
    </div>
</body>
</html>