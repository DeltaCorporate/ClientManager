<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Store;

use App\Models\Product;
use App\Models\Testimonial;
use Core\Request;
use Core\Rules;
use Core\Session;

class ProductController
{

    /*
     * View product details
     */


    public function view(Request $request, Session $session)
    {
        $id = $request->get('id');

        if ($id and is_numeric($id)) {
            $product = Product::find($id);
            $similar = Product::findAllBy("category_id", $product->category->id);
            $similar = array_map(function ($item) use ($product) {
                if ($item->id != $product->id) {
                    return $item;
                }
                return null;
            }, $similar);
            $similar = array_filter($similar);
            $cart = $session->session("cart");
            $cart = $cart ? $cart : [];
            $quantity = $cart[$product->id] ?? 1;
            $testimonials = $product->testimonials;
            foreach ($testimonials as $testimonial) {
                $like = 0;
                $dislike = 0;
                $reviews = $testimonial->reviews;
                foreach ($reviews as $review) {
                    if ($review->review =="like") {
                        $like++;
                    } else {
                        $dislike++;
                    }
                }
                $testimonial->like = $like;
                $testimonial->dislike = $dislike;

            }
            $product->testimonials = $testimonials;
            $user = $session->getUser();
            $testimonial = Testimonial::findByandBy(array("user_id" => $user->id, "product_id" => $product->id));


            render('store.product.view', compact('product', 'similar', 'quantity','testimonial'));
        } else {
            redirect('store.product.list');
        }
    }


    /*
     * View List of products
     * */
    public function list()
    {
        $products = Product::findAll();
        render("store.product.list", compact("products"));
    }

}
