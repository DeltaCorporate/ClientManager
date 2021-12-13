<?php

namespace Database\seeders;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;

class UserSeeder
{


    public function run()
    {
        $user = new User();
        $user->setEmail("test@localhost.com")
            ->setUsername("test")
            ->setPassword(password_hash("test123456789", PASSWORD_ARGON2I))
            ->flush();
        try {
            User::save($user);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
        $user2 = new User();
        $user2->setEmail("test2@localhost.com")
            ->setUsername("test2")
            ->setPassword(password_hash("test123456789", PASSWORD_ARGON2I))
            ->flush();
        var_dump([$user, $user2]);
        try {
            User::bulkCreate([$user, $user2]);
        } catch (ModelColumnNotfound $e) {
            echo $e->getMessage();
        }
    }

}