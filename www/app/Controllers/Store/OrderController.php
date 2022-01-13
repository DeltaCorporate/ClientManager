<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: OrderController
*@NameSpace: App\Controllers\Store
*/

namespace App\Controllers\Store;

use App\Models\Order;
use App\Models\User;
use Core\Request;
use Core\Session;
use FPDF;
use Mpdf\Mpdf;
use Spipu\Html2Pdf\Html2Pdf;


class OrderController
{
    public function list()
    {
        render("store.orders.list");
    }

    public function view(Request $request,Session $session)
    {
        $values = $request->getBody();
        $rules = [
            "id"=>["required","int"]
        ];
        $values = Order::associateRulesAndDatas($values,$rules,["id"]);
        Request::validateRules($values);
        $order = Order::find($values["id"]["value"]);
        if(!$order)
        {
            flash("error","Order not found");
            redirect("store.orders.list");
        }
        $products = $order->products;
        $products = json_decode($products);
        $order->products = $products;
        $user = $order->user_id;
        $user = User::find($user);
        unset($user->password);
        $subtotal = 0;
        foreach($products as $product)
        {
            $subtotal += $product->product->price * $product->quantity;
        }
        $order->subtotal = $subtotal;
        render("store.orders.view",compact("order","user"));
    }

    /**
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     * @throws \Mpdf\MpdfException
     */
    public function download(Request $request, Session $session)
    {
        $values = $request->getBody();
        $rules = [
            "id"=>["required","int"]
        ];
        $values = Order::associateRulesAndDatas($values,$rules,["id"]);
        Request::validateRules($values);
        $order = Order::find($values["id"]["value"]);
        if(!$order)
        {
            flash("error","Order not found");
            redirect("store.orders.list");
        }
        $products = $order->products;
        $products = json_decode($products);
        $order->products = $products;
        $user = $order->user_id;
        $user = User::find($user);
        unset($user->password);
        $subtotal = 0;
        foreach($products as $product)
        {
            $subtotal += $product->product->price * $product->quantity;
        }
        $order->subtotal = $subtotal;

        $content = render("store.orders.view",compact("order","user"),true);
        $pdf = new Mpdf();
        $pdf->writeHTML("<h1>Order</h1>");


        $pdf->output("$user->username"."_"."invoice#$order->id".".pdf");
    }
}
