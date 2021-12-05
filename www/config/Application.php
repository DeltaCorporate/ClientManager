<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:03
*@Class: Application
*@NameSpace: Config
*/

namespace Config;

use Database\Database;

class Application
{
    private Router $router;
    private Request $request;

    public function __construct()
    {
        new DotEnvParser();
        Database::connection();
        $this->request = new Request();

        $this->router = new Router($this->request);

    }

    public function run()
    {
        $this->router->resolve();
    }
}