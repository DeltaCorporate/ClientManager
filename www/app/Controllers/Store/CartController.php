<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: CartController
*@NameSpace: App\Controllers\Store
*/

namespace App\Controllers\Store;

use App\Models\Product;
use Core\Request;
use Core\Session;

class CartController
{
    public function view()
    {
        $cart = Session::session("cart");
        $products = [];
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            $product->asked = $quantity;
            $products[] = $product;
            $total += $product->price * $quantity;
        }
        render("store.cart.view",compact("products","total"));
    }

    public function add(Request $request, Session $session)
    {
        $values = $request->postBody();
        $cart = $session->session("cart");
        $rules = [
            "id" => ["required", "int"],
            "quantity" => ["required", "int"]
        ];
        $values = Product::associateRulesAndDatas($values, $rules, ["id", "quantity"]);
        $request->validateRules($values);
        $product = Product::find($values["id"]['value']);
        if (!$product) {
            flash("error", "Product not found");
            redirect("store.product.list");
        }
        if ($values['quantity']['value'] > $product->quantity) {
            $session->validation("quantity", "The quantity is not available");
            back();
        }
        if (!$cart) {
            $cart = [];
        }
        $cart[$product->id] = intval($values["quantity"]['value']);
        $session->setSession("cart", $cart);
        flash("success", "Product added to cart");
        redirect("store.product.list");


    }

    public function remove(Request $request, Session $session)
    {
        $values = $request->postBody();
        $cart = $session->session("cart");
        $rules = [
            "id" => ["required", "int"]
        ];
        $values = Product::associateRulesAndDatas($values, $rules, ["id"]);
        $request->validateRules($values);
        if (!$cart) {
            flash("error", "Cart is empty");
            redirect("store.product.list");
        }
        $product = Product::find($values["id"]['value']);
        if (!$product) {
            flash("error", "Product not found");
            back();
        }
        if (!$cart[$product->id]) {
            flash("error", "Product not found");
            back();
        }
        unset($cart[$product->id]);
        $session->setSession("cart", $cart);
        flash("success", "Product removed from cart");
        back();
    }

}
