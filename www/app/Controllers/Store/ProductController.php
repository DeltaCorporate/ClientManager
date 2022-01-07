<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Store;

use App\Models\Product;
use Core\Request;

class ProductController
{

    /*
     * View product details
     */


    public function view(Request $request)
    {
        $id = $request->get('id');
        if($id){
            $product = Product::find($id);
            $similar = Product::findAllBy("category_id", $product->category->id);

            render('store.product.view', compact('product', 'similar'));
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
