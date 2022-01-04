<?php

namespace Database\seeders;

class Seeder
{
    public function getToRun(): array
    {
        return [
            UserSeeder::class,
            CategorySeeder::class
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