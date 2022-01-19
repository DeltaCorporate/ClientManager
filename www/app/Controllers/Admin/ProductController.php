<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductController
*@NameSpace: App\Controllers\Admin
*/

namespace App\Controllers\Admin;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\User;
use Core\Request;
use Core\Session;

class ProductController
{
    /*
     * Display form to add new product
     */

    public function createForm()
    {
        $categories = Category::findAll();
        render("admin.store.product.create", compact('categories'));
    }

    /*
     * Add a product to store
     * */
    /**
     * @throws ModelColumnNotfound
     */
    public function create(Request $request, Session $session)
    {
        $values = $request->postBody();
        $rules = [
            'name' => ['required', "string"],
            "description" => ['required', "string"],
            "price" => ['required', "float"],
            "quantity" => ['required', "int"],
            "category_id" => ['required', "int"],
            "image" => ['required']
        ];
        $values = Product::associateRulesAndDatas($values, $rules);
        $request->validateRules($values);
        $category = Category::find($values['category_id']["value"]);
        if (!$category) {
            $session->validation("category_id", "Category not found");
            back();
        }
        $product = Product::findBy("name", $values['name']["value"]);
        if ($product) {
            $session->validation("name", "Product already exist with this name");
            back();
        }
        $product = [
            "name" => $values['name']["value"],
            "description" => $values['description']["value"],
            "price" => $values['price']["value"],
            "quantity" => $values['quantity']["value"],
            "category_id" => $values['category_id']["value"],
        ];
        Product::save($product);
        $product = Product::findBy("name", $values['name']["value"]);

        $images = $request->filesBody();
        $images = $images['image'];
        $names = $images["name"];
        foreach ($names as $key => $image) {
            $type = $images['type'][$key];
            if ($type != "image/jpeg" && $type != "image/png") {
                $session->validation("image", $image . "is not a supported format. Only jpeg and png images are allowed");
                back();
            }
            $filename = $values['product']["value"] . time() . "_" . $image;
            $path = "src/products/images/" . $filename;
            move_uploaded_file($images['tmp_name'][$key], $path);
            $imageToDb = [
                "product_id" => $values['product']["value"],
                "image" => $filename,
            ];

            ProductImages::save($imageToDb);

        }
        flash("success", "Product added successfully");
        redirect("admin.product.list");
    }

    /*
     * display form to edit product
     * */
    public function updateForm(Request $request, Session $session)
    {
        $productID = $request->get("id");
        $product = Product::find($productID);
        if (!$product) {
            flash("error", "Product not found");
            redirect("admin.product.list");
        }
        $categories = Category::findAll();
        render("admin.store.product.update", compact('product', 'categories'));
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update(Request $request, Session $session)
    {
        $values = $request->postBody();
        $rules = [
            "product"=>['required', "int"],
            'name' => ['required', "string"],
            "description" => ['required', "string"],
            "price" => ['required', "float"],
            "quantity" => ['required', "int"],
            "category_id" => ['required', "int"]
        ];
        $values = Product::associateRulesAndDatas($values, $rules,["product","category_id","name","description","price","quantity"]);
        $request->validateRules($values);
        $category = Category::find($values['category_id']["value"]);
        if (!$category) {
            $session->validation("category_id", "Category not found");
            back();
        }
        $product = Product::find($values['product']["value"]);
        if($product->name == $values["name"]["value"]){
            $session->validation("name", "Product already exist with this name");
            back();
        }
        $product = [
            "name" => $values['name']["value"],
            "description" => $values['description']["value"],
            "price" => $values['price']["value"],
            "quantity" => $values['quantity']["value"],
            "category_id" => $values['category_id']["value"],
        ];
        Product::update($values['product']["value"], $product);
        $oldImages = ProductImages::findAllBy("product_id", $values['product']["value"]);
        $images = $request->filesBody();
        $images = $images['image'];
        if(!empty($images["name"][0])){
            $names = $images["name"];
            foreach ($names as $key => $image) {
                $type = $images['type'][$key];
                if ($type != "image/jpeg" && $type != "image/png") {
                    $session->validation("image", $image . "is not a supported format. Only jpeg and png images are allowed");
                    back();
                }
                $filename = $values['product']["value"] . time() . "_" . $image;
                $path = "src/products/images/" . $filename;
                move_uploaded_file($images['tmp_name'][$key], $path);
                $imageToDb = [
                    "product_id" => $values['product']["value"],
                    "image" => $filename,
                ];

                ProductImages::save($imageToDb);

            }
        }
        $imagesToKeep = $request->postBody()['imagesToKeep'];
        $oldImagesIds = [];
        foreach ($oldImages as $image){
            $oldImagesIds[] = $image->id;
        }
        foreach ($oldImagesIds as $imageId){
            if(!in_array($imageId,$imagesToKeep)){
                ProductImages::delete($imageId);
            }
        }
        flash("success", "Product updated successfully");
        redirect("admin.product.list");
    }




    /*
     * Delete a product
     * */
    public function delete(Request $request)
    {
        $values = $request->postBody();
        $rules = [
            "id"=>['required', "int"]
        ];
        $values = Product::associateRulesAndDatas($values, $rules,["id"]);
        $request->validateRules($values);
        $product = Product::find($values['id']["value"]);
        if (!$product) {
            flash("error", "Product not found");
            redirect("admin.product.list");
        }
        $images = $product->images;
        foreach ($images as $image){
            unlink("src/products/images/" . $image->image);
        }
        Product::delete($values['id']["value"]);
        flash("success", "Product deleted successfully");
        redirect("admin.product.list");
    }

    /*
     * View List of products
     * */
    public function list()
    {
        $products = Product::findAll();
        render("admin.store.product.list", compact("products"));
    }

}
