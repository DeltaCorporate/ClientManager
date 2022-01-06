<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\ProductImages;

class ProductImagesSeeder
{


    /**
     * @throws ModelColumnNotfound
     */
    public function run()
    {
      $images = [
          [
              "product_id" => 1,
              "image"=>"test1.jpg",
          ],
          [
              "product_id" => 1,
              "image"=>"test2.jpg",
          ],
          [
              "product_id" => 2,
              "image"=>"test3.jpg",
          ],
          [
              "product_id" => 2,
              "image"=>"test4.jpg",
          ],
      ];

      ProductImages::bulkCreate($images);
    }

}
