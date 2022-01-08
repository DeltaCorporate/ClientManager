<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Store;

use App\Models\Product;
use Core\Request;
use Core\Session;

class ProductController
{

    /*
     * View product details
     */


    public function view(Request $request,Session $session)
    {
        $id = $request->get('id');
        if($id){
            $product = Product::find($id);
            $similar = Product::findAllBy("category_id", $product->category->id);
            $similar = array_map(function($item) use($product){
                if($item->id != $product->id){
                    return $item;
                }
                return null;
            }, $similar);
            $similar = array_filter($similar);
            $cart = $session->session("cart");
            $cart = $cart ? $cart : [];
            $quantity = $cart[$product->id] ?? 1;

            render('store.product.view', compact('product', 'similar', 'quantity'));
        } else{
            redirect('store.product.list');
        }

    }


    /*
     * View List of products
     * */
    public function list()
    {
        $products = Product::findAll();
//        dd($products);
        render("store.product.list",compact("products"));
    }

}
