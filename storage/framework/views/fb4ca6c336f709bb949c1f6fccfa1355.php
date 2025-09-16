<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta http-equiv="refresh" content="20;url=<?php echo e(url('/')); ?>">
</head>
<body>
    <h1>✅ Payment Successful</h1>
    <p>Your booking has been confirmed!</p>
    <p>Booking ID: <?php echo e($booking->id); ?></p>

    <p>You will be redirected to the homepage in 20 seconds...</p>
    <a href="<?php echo e(url('/')); ?>">Go to homepage now</a>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\travel\resources\views/payments/success.blade.php ENDPATH**/ ?>