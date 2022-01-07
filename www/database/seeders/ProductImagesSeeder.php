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
      $images = [];

      for($i=1;$i<7;$i++){
          $images[] = [
              "product_id" => $i,
              "image" => "test1.jpg",
          ];
          $images[] = [
              "product_id" => $i,
              "image" => "test2.jpg",
          ];
          $images[] = [
              "product_id" => $i,
              "image" => "test3.jpg",
          ];
          $images[] = [
              "product_id" => $i,
              "image" => "test4.jpg",
          ];

      }

      ProductImages::bulkCreate($images);
    }

}
