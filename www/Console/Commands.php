<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:44
*@Class: Commands
*@NameSpace: ${Console}
*/

namespace Console;

use Database\Database;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;


class Commands extends Database
{
    public function __construct()
    {
        $this->menu();
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

    public function controller(CliMenu $cliMenu)
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
                        }
                        break;
                }
            }
        }

    }

    private function menu()
    {
        try {
            (new CliMenuBuilder)
                ->setTitle('Client Manager Console')
                ->setTitleSeparator('#')
                ->addItem('Controller', function (CliMenu $cliMenu) {
                    $this->controller($cliMenu);
                })
                ->addItem("Model:create", function (CliMenu $cliMenu) {
                    $this->modelCreation($cliMenu);
                })
                ->addItem("Model:migration", function (CliMenu $cliMenu) {
                    $this->modelMigration($cliMenu);
                })
                ->addLineBreak('#')
                ->setBackgroundColour("black")
                ->setForegroundColour("white")
                ->setPadding(2, 4)
                ->setMarginAuto()
                ->build()
                ->open();
        } catch (InvalidTerminalException $e) {
            echo $e->getMessage();
        }
    }

    private function modelCreation(CliMenu $cliMenu)
    {

    }

    private function modelMigration(CliMenu $cliMenu)
    {
        $models = scandir('./database/migrations');
        array_shift($models);
        array_shift($models);
        foreach ($models as $model) {
            $modelName = pathinfo($model, PATHINFO_FILENAME);
            $modelClass = "\\Database\\migrations\\$modelName";
            $model = new $modelClass();
            $sql = $model->up();
            dump($sql);
            $migrationStatus = self::$instance->prepare($sql)->execute();
            if ($migrationStatus) {
                $this->flashSuccess($cliMenu, 'The migration for the model ' . $modelName . ' was created successfully');
            } else {
                $this->flashError($cliMenu, 'The migration for the model ' . $modelName . ' was not created successfully');
            }
        }
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
        }

    }

}