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

class CategoryController
{
    /*
     * Display form to add new category
     */

    public function createForm()
    {
        render("admin.categories.create");
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
        ];
        $values = Category::associateRulesAndDatas($values, $rules);
        $request->validateRules($values);
        $category = Category::findBy("name",$values['name']["value"]);
        if ($category) {
            $session->validation("name", "A category with this name already exists");
            back();
        }
        $category = [
            "name" => $values['name']["value"],
            "description" => $values['description']["value"],
        ];
        Category::save($category);
        flash("success", "Category added successfully");
        redirect("admin.category.list");
    }

    /*
     * display form to edit category
     * */
    public function updateForm(Request $request)
    {
        $categoryID = $request->get("id");
        $category = Category::find($categoryID);
        if (!$category) {
            flash("error", "Category not found");
            redirect("admin.category.list");
        }
        render("admin.categories.update", compact('category'));
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update(Request $request, Session $session)
    {
        $values = $request->postBody();
        $rules = [
            "category"=>['required', "int"],
            'name' => ['required', "string"],
            "description" => ['required', "string"]
        ];
        $values = Category::associateRulesAndDatas($values, $rules,["category","name","description"]);
        $request->validateRules($values);
        $category = Category::find($values['category']["value"]);
        if (!$category) {
            $session->validation("name", "Category not found");
            back();
        }
        $category = Category::findBy("name",$values['name']["value"]);
        if ($category && $category->id != $values['category']["value"]) {
            $session->validation("name", "A category with this name already exists");
            back();
        }
        $categoryData = [
            "name" => $values['name']["value"],
            "description" => $values['description']["value"],
        ];
        Category::update($values['category']["value"], $categoryData);

        flash("success", "Category updated successfully");
        redirect("admin.category.list");
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
        $values = Category::associateRulesAndDatas($values, $rules,["id"]);
        $request->validateRules($values);
        $category = Category::find($values['id']["value"]);
        if (!$category) {
            flash("error", "Category not found");
            redirect("admin.category.list");
        }
        Category::delete($values['id']["value"]);
        flash("success", "Category deleted successfully");
        redirect("admin.category.list");
    }

    /*
     * View List of products
     * */
    public function list()
    {
        $categories = Category::findAll();
        render("admin.categories.list", compact("categories"));
    }

}
