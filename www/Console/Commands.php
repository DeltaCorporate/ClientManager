<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:44
*@Class: Commands
*@NameSpace: ${Console}
*/

namespace Console;

use Console\Commands\MakeCommands\middleware;
use Console\Commands\MakeCommands\migration as makeMigration;
use Console\Commands\MakeCommands\model;
use Console\Commands\MigrationCommands\migration;
use Database\Database;
use Database\seeders\Seeder;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;


class Commands extends Database
{
    public function __construct()
    {
        $this->menu();
        $this->givePerms();
    }

    public function flashError(CliMenu $cliMenu, string $msg)
    {
        $flash = $cliMenu->flash($msg);
        $flash->getStyle()->setBg('red')->setFg('black');
        $flash->display();
    }

    public function flashSuccess(CliMenu $cliMenu, string $msg)
    {
        $flash = $cliMenu->flash($msg);
        $flash->getStyle()->setBg('green')->setFg('black');
        $flash->display();

    }

    public function run_seeders(CliMenu $cliMenu)
    {
        $seederInstance = new Seeder();
        $seederInstance->run();
        $this->flashSuccess($cliMenu, 'Toutes les seeders ont été commit à la bdd');
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
        }
    }

    public function make_controller(CliMenu $cliMenu)
    {
        $result = $cliMenu->askText();
        $result->getStyle()
            ->setBg('blue')
            ->setFg('black');
        $result = $result->setPromptText('Enter the controller name')
            ->setPlaceholderText('')
            ->setValidationFailedText('The Controller name must be only like controller or Directory/controller')
            ->ask();
        $controller = $result->fetch();
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
                        shell_exec('touch ./app/Controllers/' . $controllerName . '.php');
                        $content = "<?php" . PHP_EOL . "namespace App\Controllers;" . PHP_EOL . PHP_EOL . "class $controllerName " . PHP_EOL . "{" . PHP_EOL . PHP_EOL . "}";
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
                        shell_exec('mkdir -p ./app/Controllers/' . $controllerSplited[0]);
                        shell_exec('touch ./app/Controllers/' . $controllerSplited[0] . '/' . $controllerName . '.php');
                        $content = "<?php" . PHP_EOL . "namespace App\Controllers\\$controllerSplited[0];" . PHP_EOL . PHP_EOL . "class $controllerName " . PHP_EOL . "{" . PHP_EOL . PHP_EOL . "}";
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

    private function menu()
    {
        try {
            (new CliMenuBuilder)
                ->setTitle('Client Manager Console')
                ->setTitleSeparator('#')
                ->addItem('make:controller', function (CliMenu $cliMenu) {
                    $this->make_controller($cliMenu);
                })
                ->addItem("make:model", function (CliMenu $cliMenu) {
                    call_user_func([new model(), 'make'], $cliMenu);
                })
                ->addItem("make:migration", function (CliMenu $cliMenu) {
                    call_user_func([new makeMigration(), 'make'], $cliMenu);
                })
                ->addItem("make:middleware", function (CliMenu $cliMenu) {
                    call_user_func([new middleware(), "make"], $cliMenu);
                })
                ->addItem("migrate", function (CliMenu $cliMenu) {
                    call_user_func([new migration(), "migrate"], $cliMenu);
                })
                ->addItem("seeders:run", function (CliMenu $cliMenu) {
                    $this->run_seeders($cliMenu);
                })
                ->addLineBreak('#')
                ->setBackgroundColour("black")
                ->setForegroundColour("white")
                ->setPadding(2, 4)
                ->setMarginAuto()
                ->build()
                ->open();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
            echo $e->getMessage();
        }
        $this->givePerms();
    }



    private function givePerms()
    {
        shell_exec("chmod -R 777 ./");
    }

}