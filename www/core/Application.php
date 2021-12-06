<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:03
*@Class: Application
*@NameSpace: Config
*/

namespace Core;

use Database\Database;

class Application
{
    private Router $router;
    private Request $request;
    private Session $session;

    public function __construct()
    {
        new DotEnvParser();
        Database::connection();
        $this->request = new Request();

        $this->session = new Session();
        $this->router = new Router($this->request, $this->session);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

    }

    public function run()
    {
        $this->router->resolve();
    }
}