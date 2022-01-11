<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use App\Models\User_data;

class UserSeeder
{


    public function run()
    {
        $users = [
            [
                "email" => "test@localhost.com",
                "username" => "test",
                "verified"=>0,
                "password" => password_hash("test123456789", PASSWORD_ARGON2I),
            ],
            [
                "email" => "test2@localhost.com",
                "username" => "test2",
                "verified"=>0,
                "password" => password_hash("test123456789", PASSWORD_ARGON2I),
            ]
        ];

        $userDatas = [
            [
                "user_id" => 1,
                "avatar" => "defaultAvatar.svg",
                "roles" => "[1,2]"
            ],
            [
                "user_id" => 2,
                "avatar" => "defaultAvatar.svg",
                "roles" => "[1]"
            ]
        ];

        try {
            User::bulkCreate($users);
            User_data::bulkCreate($userDatas);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
    }

}