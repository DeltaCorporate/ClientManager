<?php

namespace App\Models;

class User
{
    public function createTable($name){
        $table = new \Table($name);
        $table->id()
            ->string('name')
            ->string('email')
            ->end();
        $table->create();

    }
}