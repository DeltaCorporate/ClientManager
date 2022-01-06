<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Admin;

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
        $product = Product::find($id);
        $similar = Product::findAllBy("category_id", $product->category->id);

        render('admin.product.view', compact('product', 'similar'));

    }


    /*
     * View List of products
     * */
    public function list()
    {
        //TODO: RENDER LIST OF PRODUCTS
    }

    /*
     * UPDATE PRODUCT DETAILS : view
     */

    public function update()
    {
        //TODO: form to update PRODUCT
    }

    /*
     * Delete product
     * */

    public function delete()
    {
        //TODO: DELETE PRODUCT
    }

}
