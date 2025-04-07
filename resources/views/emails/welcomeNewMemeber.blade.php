<!-- resources/views/emails/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Service</title>
</head>
<body>
    <h1>Welcome to Our Service!</h1>
    <p>Your account has been created successfully. Here are your login details:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please log in and change your password as soon as possible.</p>
</body>
</html>
