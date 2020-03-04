<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cancel</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Alright</h4>
        <p>Your transaction was cancelled.</p>
        <hr>
        <small class="mb-0 font-italic">Transaction ID: <?= $guid ?></small>
    </div>
</div>

</body>
</html>
