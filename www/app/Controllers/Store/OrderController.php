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
use Dompdf\Dompdf;


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

        $content = render("store.orders.download",compact("order","user"),true);
        $pdf = new  Dompdf();
        $pdf->loadHtml($content);
        $options = $pdf->getOptions();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->setIsPhpEnabled(true);
        $pdf->setOptions($options);
        $pdf->setPaper('A4');
        $pdf->render();
        $pdf->stream($user->username."_order_".$order->id.".pdf",array("Attachment"=>1));

    }
}
