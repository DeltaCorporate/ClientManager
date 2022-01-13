<?php

namespace App\Controllers\Store;

use App\Models\Product;
use App\Models\Remark;
use App\Models\User;
use Core\Request;
use Core\Session;

class RemarkController
{

    public function postComment()
    {
        $values = Request::postBody();
        $rules = [
            "txt" => ["string", "length:6:255"],
        ];
        $values = User::associateRulesAndDatas($values, $rules);
        Request::validateRules($values);
        /*
       A terminer...
        */
    }
    /*
     * View List of remarks for a particular product
     * */
    public function list(Request $request)
    {
        $id = $request->get('id');
        if($id) {
            $product = Product::find($id);
            $remarks = Remark::findAllBy("product_id", $product->id);
            render("store.product.remark", compact("remarks"));
        }
    }

}