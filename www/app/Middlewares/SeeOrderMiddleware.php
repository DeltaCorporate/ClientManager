<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AuthMiddleware
*@NameSpace: App\Middlewares
*/

namespace App\Middlewares;

use App\Models\Order;
use Core\Middleware;
use Core\Request;
use Core\Session;

class SeeOrderMiddleware extends Middleware
{

    public function run(Request $request, Session $session): bool
    {
        $body = $request->getBody();
        if(!isset($body['id'])){
            return false;
        }
        $id = $body['id'];
        $order = Order::find($id);
        if($order){
            $user_id = $order->user_id;
            $user = $session->getUser();
            if($user->id != $user_id){
                return false;
            }
        }


        return true;

    }

    public function error()
    {
        flash("error","There was an error while trying to see this order.");
        redirect("store.orders.list");
    }
}
