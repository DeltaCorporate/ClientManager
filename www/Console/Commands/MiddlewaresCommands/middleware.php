<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 18:00
*@Class: middleware
*@NameSpace: Console
*/

namespace Console\Commands\MiddlewaresCommands;

use Console\Commands\Utils;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use function dump;

class middleware extends Utils
{


    /**
     * @var string
     */

    public function make(CliMenu $cliMenu)
    {
        $middleware = $this->ask($cliMenu, 'Enter the name of the middleware','The middleware name must be only like Authentification or CheckingRole. No directory is supported.');
        if (file_exists('./app/Middlewares/' . $middleware . 'Middleware.php')) {
            $this->flashError($cliMenu, 'The Controller already exists');
        } else {
            $middlewareSplitted = explode('/', $middleware);
            if (sizeof($middlewareSplitted) > 1) {
                $this->flashError($cliMenu, 'The middleware name must be only like Authentification or CheckingRole. No directory is supported.');
            } else {
                $middlewareName = $middleware . 'Middleware';
                shell_exec("curl https://raw.githubusercontent.com/DeltaCorporate/frametemplates/main/commands/makecommands/middleware.php -o ./app/Middlewares/" . $middlewareName . ".php");
                $content = file_get_contents('./app/Middlewares/' . $middlewareName . '.php');
                $content = str_replace('NAME', $middlewareName, $content);
                file_put_contents('./app/Middlewares/' . $middlewareName . '.php', $content);
                $this->flashSuccess($cliMenu, 'The middleware created successfully\nCheck the file in the app/Middelewares/' . $middlewareName . '.php');
                try {
                    $cliMenu->close();
                } catch (InvalidTerminalException $e) {
                    dump('Impossible to close the cli');
                }
            }
        }
        $this->givePerms();

    }
}