<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;

class UserSeeder
{


    public function run()
    {
        $user = new User();
        $user->setEmail("test@localhost.com");
        $user->setUsername("test");
        $user->setPassword(password_hash("test123456789", PASSWORD_ARGON2I));
        $user = $user->getUser();
        $user2 = new User();
        $user2->setEmail("test2@localhost.com");
        $user2->setUsername("test2");
        $user2->setPassword(password_hash("test123456789", PASSWORD_ARGON2I));
        $user2 = $user2->getUser();

        try {
            User::bulkCreate(array($user, $user2));
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
    }

}