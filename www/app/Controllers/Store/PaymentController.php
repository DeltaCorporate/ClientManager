<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: PaymentController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Store;

use App\Models\Product;
use Core\Request;
use Core\Session;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaymentController
{
    public static function createPaymentContext(): ApiContext
    {
        return new ApiContext(new OAuthTokenCredential($_SERVER["SANDBOX_CLIENT_ID"], $_SERVER["SANDBOX_SECRET"]));
    }

    public function setThePayment(Request $request, Session $session): Payment
    {
        $apiContext = self::createPaymentContext();
        $payer = (new Payer())
            ->setPaymentMethod('paypal');
        $redirectUrls = (new RedirectUrls())
            ->setReturnUrl(url("store.payment.status"))
            ->setCancelUrl(url("store.payment.status"));
        $listOfItems = new ItemList();
        $currentCart = $session->session("cart");
        $cart = [];
        foreach ($currentCart as $key => $item) {
            $cart[] = [
                "product" => Product::find($key),
                "quantity" => $item
            ];
        }
        $subtotal = 0;
        foreach ($cart as $item) {
            $listOfItems->addItem(
                (new Item())
                    ->setName($item["product"]->name)
                    ->setCurrency('EUR')
                    ->setQuantity($item["quantity"])
                    ->setPrice($item["product"]->price)
            );
            $subtotal += $item["product"]->price * $item["quantity"];
        }
        $total = $subtotal + ($subtotal * 0.2);

        $details = (new Details())
            ->setSubtotal($subtotal)
            ->setTax(0.2 * $subtotal);

        $amount = (new Amount())
            ->setTotal($total)
            ->setCurrency('EUR')
            ->setDetails($details);

        $payment = (new Payment())
            ->setIntent("sale")
            ->setRedirectUrls($redirectUrls)
            ->setPayer($payer);
        $transaction = (new Transaction())
            ->setItemList($listOfItems)
            ->setDescription("Payment on " . $_SERVER["APP_URL"])
            ->setAmount($amount)
        ->setCustom($session->getUser()->id);
        $payment->setTransactions([$transaction]);
        return $payment;
    }

    public function checkout(Request $request, Session $session)
    {


        try {
            $payment = self::setThePayment($request, $session);
            $payment->create(self::createPaymentContext());
            redirect($payment->getApprovalLink());
        } catch (PayPalConnectionException $e) {
            flash("error", "The payment failed, please try again later");
            back();
        }
    }


    public function getPaymentStatus(Request $request)
    {
        $paymentId = $request->get("paymentId");
        $payerId = $request->get("PayerID");
        $payment = Payment::get($paymentId, self::createPaymentContext());
        $paymentExecution = (new PaymentExecution())
            ->setPayerId($payerId)
            ->setTransactions($payment->getTransactions());
        try {
            $payment->execute($paymentExecution, self::createPaymentContext());
            $userID = $payment->getTransactions()[0]->getCustom();

            flash("success", "The payment was successful");
            redirect("store.cart.view");
        } catch (PayPalConnectionException $e) {
            flash("error", "The payment failed, please try again later");
            redirect("store.cart.view");
        }
    }
}
