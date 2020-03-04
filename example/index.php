<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;
$renderer = new PhpRenderer(__DIR__ . '/pages');

$cps = new Wsu\Cps\CpsApi('MERCHANT_ID', 'TRANS_TYPE', 'local');

$app->get('/', function (Request $request, Response $response, $args) use ($renderer) {
    // the user selects the goods to buy and submits the order;
    // they will be redirected to the WSU CPS payment page
    return $renderer->render($response, 'home.view.php');
});


$app->post('/', function (Request $request, Response $response, $args) use ($cps, $renderer) {
    $price = [
        'apples' => 3.99,
        'pears' => 3.99,
        'oranges' => 3.99,
    ];

    $first = $request->getParam('first');
    $last = $request->getParam('last');
    $email = $request->getParam('email');
    $item = $request->getParam('item');
    $quantity = $request->getParam('quantity');

    $amount = $price[$item] * $quantity;

    try {
        // call the web service to 'set-up' the transaction, providing the necessary information;
        // the service will return the url where the user can provide the payment info and complete the purchase
        $cps_response = $cps->AuthCapRequestWithCancelURL(
            '/result',
            '/cancel',
            '/postback',
            $amount,
            $first,
            $last,
            // this data will be saved in the transaction records and available for later usage
            [
                'cart' => ['item' => $item, 'quantity' => $quantity],
                'user' => ['first' => $first, 'last' => $last, 'email' => $email],
            ]
        );
    } catch (\Throwable $t) {
        return $renderer->render($response, 'home.view.php', ['error' => 'Connection with WSU CPS failed.']);
    }

    if (!$cps_response->isValid()) {
        return $renderer->render($response, 'home.view.php', ['error' => 'Got an invalid response.']);
    }

    // redirect the user to the Cybersource payment page
    return $response->withHeader('Location', $cps_response->redirect_url)->withStatus(302);
});


$app->get('/result', function (Request $request, Response $response, $args) use ($cps, $renderer) {
    // once the payment has been submitted the user will be redirected to this url,
    // where you typically confirm the user that the transaction has been submitted
    try {
        $guid = $request->getParam('GUID');
        $payment = $cps->ReadPaymentAuthorization($guid);
    } catch (\Throwable $t) {
        return $renderer->render($response, 'result.view.php', ['error' => 'Connection with WSU CPS failed.']);
    }

    if (!$payment->payload) {
        return $renderer->render($response, 'result.view.php', ['error' => 'Error retrieving the transaction.']);
    }

    $user = $payment->payload->user;
    $cart = $payment->payload->cart;

    return $renderer->render($response, 'result.view.php', ['user' => $user, 'cart' => $cart, 'guid' => $guid]);
});


$app->get('/cancel', function (Request $request, Response $response, $args) use ($renderer) {
    // if the users hits cancel without submitting a payment, WSU CPS will redirect him/her to this url
    return $renderer->render($response, 'cancel.view.php', ['guid' => $request->getParam('GUID')]);
});


$app->post('/postback', function (Request $request, Response $response, $args) use ($cps) {
    // the WSU CPS will post here once the transaction has been completed, providing the transaction GUID;
    // here you should call AuthCaptureResponse, which will set the transaction as completed in the CPS registry;
    // you also typically perform some state change (e.g. database update) then notify/email the user of the result

    $guid = $request->getParam('GUID');
    // complete the transaction
    // Note: if you don't call this in your process WSU CPS will flag the transaction and generate an exception
    $transaction = $cps->AuthCaptureResponse($guid);

    $success = $transaction->isApproved();
    // update your system accordingly
    // notify the user about the transaction result
});


$app->run();
