<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: OrderController
*@NameSpace: App\Controllers\Store
*/

namespace App\Controllers\Store;

use App\Models\Order;
use Core\Request;
use Core\Session;

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
        render("store.orders.view",compact("products","order"));
    }
}
