<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Product;

class ProductSeeder
{


    public function run()
    {
        $products = [
            [
                "name" => "Product 1",
                "description" => "Product 1 description",
                "price" => "10.00",
                "category_id" => 1,
                "quantity" => 10,
            ],
            [
                "name" => "Product 2",
                "description" => "Product 2 description",
                "price" => "20.00",
                "category_id" => 2,
                "quantity" => 20,
            ],
            [
                "name" => "Product 3",
                "description" => "Product 3 description",
                "price" => "30.00",
                "category_id" => 3,
                "quantity" => 30,
            ]
        ];
        try {
            Product::bulkCreate($products);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
    }

}
