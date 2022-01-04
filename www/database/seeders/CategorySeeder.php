<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/01/2022 at 08:53
*@Class: CategorySeeser
*@NameSpace: Database\seeders
*/

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Category;

class CategorySeeder
{
    public function run()
    {
        $categories = [
            [
                "name" => "Categorie 1",
                "description" => "Description de la categorie 1",
            ],
            [
                "name" => "Categorie 2",
                "description" => "Description de la categorie 2",
            ],
            [
                "name" => "Categorie 3",
                "description" => "Description de la categorie 3",
            ]
        ];

        try {
            Category::bulkCreate($categories);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }

    }
}