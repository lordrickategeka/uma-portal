<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">Payment Failed</div>
                    <div class="card-body">
                        <h5 class="card-title">Payment was not successful</h5>
                        <p class="card-text">Your transaction could not be completed. Please try again.</p>
                        <p>Reference: {{ request('reference') }}</p>
                        <a href="#" class="btn btn-primary">Try Again</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>