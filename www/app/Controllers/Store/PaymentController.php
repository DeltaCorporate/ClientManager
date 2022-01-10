<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: PaymentController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Store;

use Core\Request;
use Core\Session;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaymentController
{

    public function checkout(Request $request, Session $session)
    {
        $apiContext = new ApiContext(new OAuthTokenCredential($_SERVER["SANDBOX_CLIENT_ID"],$_SERVER["SANDBOX_SECRET"]));
        $payment = new Payment();
        $payment->create($apiContext);
        dd($payment);
    }
}
