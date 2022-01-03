<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:03
*@Class: Application
*@NameSpace: Config
*/

namespace Core;

use App\Exceptions\MiddlewareException;
use Database\Database;

class Application
{
    private Router $router;
    private Request $request;
    private Session $session;

    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        new DotEnvParser();
        Database::connection();
        $this->request = new Request();
        $this->session = new Session();


        $this->router = new Router($this->request, $this->session);


    }

    /**
     * @throws MiddlewareException
     */
    public function run()
    {
        $this->router->resolve();
    }
}