<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<?php
echo isset($error) ? '
    <div class="alert alert-danger" role="alert">' . $error . '</div>
    ' : '
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Hooray!</h4>
        <p>
            Thank you ' . $user['first'] . ' for your order of ' . $cart['quantity'] . ' ' . $cart['item'] . '.<br>
            You will receive a notification by email confirming the result of your transaction.
        </p>
        <hr>
        <small class="mb-0 font-italic">Transaction ID: ' . $guid . '.</small>
    </div>';
?>

</body>
</html>
