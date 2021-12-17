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
    private Mailer $mailer;

    public function __construct()
    {
        new DotEnvParser();
        Database::connection();
        $this->request = new Request();
        $this->session = new Session();
        $this->mailer = new Mailer();


        $this->router = new Router($this->request, $this->session,$this->mailer);


    }

    public function run()
    {
        $this->router->resolve();
    }
}