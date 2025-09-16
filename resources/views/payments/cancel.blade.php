<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Cancelled</title>
    <meta http-equiv="refresh" content="20;url={{ url('/') }}">
</head>
<body>
    <h1>⚠️ Payment Cancelled</h1>
    <p>Your payment was cancelled. No money has been taken.</p>
    <p>You can try booking again if you still want the tour.</p>

    <p>You will be redirected to the homepage in 20 seconds...</p>
    <a href="{{ url('/') }}">Go to homepage now</a>
</body>
</html>
