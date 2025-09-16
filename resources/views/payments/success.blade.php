<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta http-equiv="refresh" content="20;url={{ url('/') }}">
</head>
<body>
    <h1>✅ Payment Successful</h1>
    <p>Your booking has been confirmed!</p>
    <p>Booking ID: {{ $booking->id }}</p>

    <p>You will be redirected to the homepage in 20 seconds...</p>
    <a href="{{ url('/') }}">Go to homepage now</a>
</body>
</html>
