<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: PaymentController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Store;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Order;
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
use function MongoDB\BSON\toJSON;

class PaymentController
{
    public static function createPaymentContext(): ApiContext
    {
        return new ApiContext(new OAuthTokenCredential($_SERVER["SANDBOX_CLIENT_ID"], $_SERVER["SANDBOX_SECRET"]));
    }

    public function setThePayment(Request $request, Session $session): array
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
        return [$payment, $cart];
    }

    public function checkout(Request $request, Session $session)
    {


        try {
            $createdPayment = self::setThePayment($request, $session);
            $payment = $createdPayment[0];
            $payment->create(self::createPaymentContext());
            $cart = $createdPayment[1];
            $order = [
                "user_id" => $session->getUser()->id,
                "products" => json_encode($cart),
                "status" => "pending",
            ];
            Order::save($order);
            redirect($payment->getApprovalLink());
        } catch (PayPalConnectionException|ModelColumnNotfound $e) {
            flash("error", "The payment failed, please try again later");
            back();
        }
    }


    /**
     * @throws ModelColumnNotfound
     */
    public function getPaymentStatus(Request $request,Session $session)
    {
        $paymentId = $request->get("paymentId");
        $payerId = $request->get("PayerID");
        if(!$paymentId || !$payerId){
            flash("error", "The payment failed, please try again later");
            redirect("store.cart.view");
        }
        $payment = Payment::get($paymentId, self::createPaymentContext());
        $user = $payment->getTransactions()[0]->getCustom();
        $order = Order::findBy("user_id",$user);
        $paymentExecution = (new PaymentExecution())
            ->setPayerId($payerId)
            ->setTransactions($payment->getTransactions());
        try {
            $payment->execute($paymentExecution, self::createPaymentContext());
            Order::update($order->id,["status" => "paid"]);
            $session->clearSession("cart");
            flash("success", "The payment was successful");
            redirect("store.cart.view");
        } catch (PayPalConnectionException $e) {
            Order::update($order->id,["status" => "cancelled"]);
            flash("error", "The payment failed, please try again later");
            redirect("store.cart.view");
        }
    }
}
