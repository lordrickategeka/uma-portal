<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank you for contacting us</title>
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
        <h1>Thank You For Contacting Us</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $contactEntry->name }},</p>
        
        <p>Thank you for reaching out to us. We have received your message and our team will be in touch with you shortly.</p>
        
        <p>Here's a summary of the information you provided:</p>
        
        <ul>
            <li><strong>Name:</strong> {{ $contactEntry->name }}</li>
            <li><strong>Email:</strong> {{ $contactEntry->email }}</li>
            <li><strong>Phone:</strong> {{ $contactEntry->phone }}</li>
            <li><strong>Subject:</strong> {{ $contactEntry->subject }}</li>
            <li><strong>Message:</strong> {{ $contactEntry->message }}</li>
        </ul>
        
        <p>If you need to provide additional information or have any questions in the meantime, please don't hesitate to reply to this email.</p>
        
        <p>We appreciate your interest and look forward to connecting with you soon.</p>
        
        <p>Best regards,<br>
        The Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply directly to this email.</p>
    </div>
</body>
</html>