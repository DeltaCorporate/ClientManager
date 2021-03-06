<?php

namespace Database\seeders;

class Seeder
{
    public function getToRun(): array
    {
        return [
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImagesSeeder::class
        ];
    }

    public function run()
    {
        foreach ($this->getToRun() as $class) {
            $class = new $class;
            $class->run();
        }
    }
}