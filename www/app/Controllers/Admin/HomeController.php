<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: HomeController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\User;

class HomeController
{
    public function index()
    {
        $users["verified"] = User::countBy("verified",true)->total;
        $users["unverified"] = User::countBy("verified",false)->total;
        $orders["paid"] = Order::countBy("status","paid")->total;
        $orders["pending"] = Order::countBy("status","pending")->total;
        $orders["cancelled"] = Order::countBy("status","cancelled")->total;

        render("admin.dashboard",compact("users","orders"));
    }
}
