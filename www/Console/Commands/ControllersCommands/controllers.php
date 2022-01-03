<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 21:16
*@Class: controllers
*@NameSpace: Console\Commands\ControllersCommands
*/

namespace Console\Commands\ControllersCommands;

use Console\Commands\Utils;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class controllers extends Utils
{

    public function make(CliMenu $cliMenu)
    {

        $controller = $this->ask($cliMenu, 'Enter the controller name', 'The Controller name must be only like controller or Directory/controller');
        if (file_exists('./app/Controllers/' . $controller . 'Controller.php')) {
            $this->flashError($cliMenu, 'The Controller already exists');
        } else {
            $controllerSplited = explode('/', $controller);
            if (sizeof($controllerSplited) > 2) {
                $this->flashError($cliMenu, 'The Controller name must be only like controller or Directory/controller');
            } else {
                switch (sizeof($controllerSplited)) {
                    case 1:
                        $controllerName = $controller . 'Controller';
                        shell_exec("curl -s https://raw.githubusercontent.com/DeltaCorporate/frametemplates/main/commands/makecommands/controller.php -o ./app/Controllers/" . $controllerName . ".php");
                        $content = file_get_contents('./app/Controllers/' . $controllerName . '.php');
                        $content = str_replace('App\Controllers\NAMESPACE', "App\Controllers", $content);
                        $content = str_replace('NAME', $controllerName, $content);
                        file_put_contents('./app/Controllers/' . $controllerName . '.php', $content);
                        $this->flashSuccess($cliMenu, 'The Controller created successfully\nCheck the file in the app/Controllers/' . $controllerName . '.php');
                        try {
                            $cliMenu->close();
                        } catch (InvalidTerminalException $e) {
                            dump('Impossible to close the cli');
                        }
                        break;
                    case 2:
                        $controllerName = $controllerSplited[1] . 'Controller';
                        shell_exec("curl -s  https://raw.githubusercontent.com/DeltaCorporate/frametemplates/main/commands/makecommands/controller.php -o ./app/Controllers/" . $controllerSplited[0] . "/" . $controllerName . ".php --create-dirs");
                        $content = file_get_contents('./app/Controllers/' . $controllerSplited[0] . '/' . $controllerName . '.php');
                        $content = str_replace('NAMESPACE', $controllerSplited[0], $content);
                        $content = str_replace('NAME', $controllerName, $content);
                        file_put_contents('./app/Controllers/' . $controllerSplited[0] . '/' . $controllerName . '.php', $content);
                        $this->flashSuccess($cliMenu, 'The Controller created successfully\nCheck the file in the app/Controllers/' . $controllerSplited[0] . '/' . $controllerName . '.php');
                        try {
                            $cliMenu->close();
                        } catch (InvalidTerminalException $e) {
                            dump('Impossible to close the cli');
                        }
                        break;
                }
            }
        }
        $this->givePerms();

    }

}