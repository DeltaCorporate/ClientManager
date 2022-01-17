<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: TestimonialsController
*@NameSpace: App\Controllers\Store
*/

namespace App\Controllers\Store;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Product;
use App\Models\Review;
use App\Models\Testimonial;
use Core\Request;
use Core\Session;

class TestimonialsController
{

    /**
     * @throws ModelColumnNotfound
     */
    public function comment(Request $request, Session $session)
    {
        $values  = $request->postBody();
        $rules = [
            "idProduct"=>["required","int"],
            "comment"=>["required","string","length:1:500"]
        ];
        $values = Testimonial::associateRulesAndDatas($values,$rules,["idProduct","comment"]);
        $request->validateRules($values);
        $product = Product::find($values["idProduct"]["value"]);

        if(!$product){
            flash("error","Product not found");
           redirect("store.product.list");
        }

        $testimonial = Testimonial::findByandBy(array("product_id"=>$values["idProduct"]["value"],"user_id"=>$session->getUser()->id));
        if($testimonial){
            flash("error","You already commented this product");
            back();
        }
        $testimonialData = [
            "user_id"=>$session->getUser()->id,
            "product_id"=>$values["idProduct"]["value"],
            "comment"=>$values["comment"]["value"],
        ];
        Testimonial::save($testimonialData);
        flash("success","Your comment has been added successfully");
        back();
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update(Request $request, Session $session)
    {
        $values  = $request->postBody();
        $rules = [
            "idProduct"=>["required","int"],
            "comment"=>["required","string","length:1:500"]
        ];

        $values = Testimonial::associateRulesAndDatas($values,$rules,["idProduct","comment"]);
        $request->validateRules($values);
        $product = Product::find($values["idProduct"]["value"]);

        if(!$product){
            flash("error","Product not found");
            redirect("store.product.list");
        }

        $testimonial = Testimonial::findByandBy(array("product_id"=>$values["idProduct"]["value"],"user_id"=>$session->getUser()->id));
        if(!$testimonial){
            flash("error","You haven't commented this product");
            back();
        }

        Testimonial::update($testimonial->id,array("comment"=>$values["comment"]["value"]));
        flash("success","Your comment has been updated successfully");
        back();
    }

    public function delete(Request $request,Session $session)
    {
        $values  = $request->postBody();
        $rules = [
            "id"=>["required","int"]
        ];

        $values = Testimonial::associateRulesAndDatas($values,$rules,["id"]);
        $request->validateRules($values);
        $testimonial = Testimonial::findByandBy(array("id"=>$values["id"]["value"],"user_id"=>$session->getUser()->id));
        if(!$testimonial){
            flash("error","Testimonial not found");
            back();
        }

        Testimonial::delete($testimonial->id);
        flash("success","Your comment has been deleted successfully");
        back();


    }

    /**
     * @throws ModelColumnNotfound
     */
    public function likeGestion(Request $request, Session $session)
    {
        $values = $request->postBody();
        $rules = [
            "idComment" => ["required", "int"],
            "review" => ["required", "string"]
        ];
        $values = Testimonial::associateRulesAndDatas($values, $rules, ["idComment", "review"]);
        $request->validateRules($values);
        $user = $session->getUser();
        $review = Review::findByandBy(array("user_id" => $user->id, "testimonial_id" => $values["idComment"]["value"]));
        $testimonial = Testimonial::find($values["id"]["value"]);

        if ($review) {
            $note = $review->review;
            if ($note == $values["review"]["value"]) {
                Review::delete($review->id);
                $message = "You unliked this comment";
            } else {
                Review::update($review->id, array("review" => $values["review"]["value"]));
                $message = "You liked this comment";
            }
        } else {
            $datas = array(
              "user_id" => $user->id,
              "testimonial_id" => $values["idComment"]["value"],
              "review" => $values["review"]["value"]
            );
            Review::save($datas);
            $message = "You liked this comment";
        }
        flash("success", $message);
    }

}
