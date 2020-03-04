<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">

<?php
if (isset($error)) {
echo '
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> ' . $error . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>'; }
?>

    <div class="card">
        <div class="card-body">
            <h1>Purchase</h1>

            <form action="/" method="post">
                <div class="form-row">
                    <div class="col-md-4 mb-2">
                        <input id="first" name="first" type="text" class="form-control" placeholder="First name">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input id="last" name="last" type="text" class="form-control" placeholder="Last name">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group align-middle mb-2 mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="item" id="apples" value="apples">
                                <label class="form-check-label" for="apples">Apples (3.99$/lb)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="item" id="pears" value="pears">
                                <label class="form-check-label" for="pears">Pears (1.99$/lb)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="item" id="oranges" value="oranges">
                                <label class="form-check-label" for="oranges">Oranges (2.99$/lb)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">lb</span>
                            </div>
                            <input type="number" id="quantity" name="quantity" class="form-control"
                                   placeholder="Quantity">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary float-right">Buy</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

</body>
</html>
