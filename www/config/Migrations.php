<?php

namespace Config;

class Migrations
{
    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        $migrations = glob("../ressources/migrations/");

        foreach ($migrations as $migration) {
            require_once $migration;
        }
    }

}