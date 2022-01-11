<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\Role;

class RoleSeeder
{


    public function run()
    {
        $roles = [
            [
                "name" => "client",
                "description" => "Role client"
            ],
            [
                "name" => "administrator",
                "description" => "Role admin"
            ]
        ];
        try {
            Role::bulkCreate($roles);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
    }

}
