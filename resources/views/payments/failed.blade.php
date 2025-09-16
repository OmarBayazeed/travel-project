<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <meta http-equiv="refresh" content="20;url={{ url('/') }}">
</head>
<body>
    <h1>❌ Payment Failed</h1>
    <p>{{ $message ?? 'Something went wrong with your payment.' }}</p>

    <p>You will be redirected to the homepage in 20 seconds...</p>
    <a href="{{ url('/') }}">Go to homepage now</a>
</body>
</html>
