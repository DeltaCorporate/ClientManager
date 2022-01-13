<?php

namespace App\Controllers\Store;

use App\Models\Product;
use App\Models\Remark;
use Core\Request;

class RemarkController
{
    /*
     * View List of remarks for a particular product
     * */
    public function listRemarks(Request $request)
    {
        $id = $request->get('id');
        if($id) {
            $product = Product::find($id);
            $remarks = Remark::findAllBy("product_id", $product->id);
            render("store.product.remark", compact("remarks"));
        }
    }
}