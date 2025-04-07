<!-- resources/views/payment/error.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-card {
            margin-top: 80px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .error-header {
            background-color: #dc3545;
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        .error-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .btn-retry {
            background-color: #0d6efd;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-retry:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }
        .btn-home {
            background-color: #6c757d;
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            font-weight: 500;
            margin-left: 10px;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background-color: #5c636a;
            transform: translateY(-2px);
        }
        .error-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card error-card">
                    <div class="error-header text-center">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                            ⚠️
                        </div>
                        <h2>Payment Processing Error</h2>
                    </div>
                    <div class="card-body text-center py-5">
                        <h4 class="mb-4">We encountered an issue while processing your payment</h4>
                        
                        <p class="lead mb-4">
                            Transaction Reference: <strong>{{ request('reference') ?? 'Not Available' }}</strong>
                        </p>
                        
                        @if(session('error_message'))
                            <div class="error-details">
                                {{ session('error_message') }}
                            </div>
                        @endif
                        
                        <p class="mt-4">
                            This could be due to a technical issue on our end or with the payment provider.
                            Your account has not been charged.
                        </p>
                        
                        <div class="mt-5">
                            <a href="{{ route('payments.form') }}" class="btn btn-retry">Try Again</a>
                            <a href="{{ url('/') }}" class="btn btn-home">Back to Home</a>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted">
                                If you continue to experience issues, please contact our support team.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>