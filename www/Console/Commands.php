<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:44
*@Class: Commands
*@NameSpace: ${Console}
*/

namespace Console;

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

    public function run_seeders(CliMenu $cliMenu){
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
                    $this->make_model($cliMenu);
                })
                ->addItem("make:migration", function (CliMenu $cliMenu) {
                    $this->make_migration($cliMenu);
                })
                ->addItem("migrate", function (CliMenu $cliMenu) {
                    $this->migrate($cliMenu);
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

    private function make_model(CliMenu $cliMenu)
    {
        $prompt = $cliMenu->askText();
        $prompt->getStyle()
            ->setBg('blue')
            ->setFg('black');
        $prompt = $prompt->setPromptText('Enter the name of the model')
            ->setPlaceholderText('')
            ->setValidationFailedText('The Controller name must be only like controller or Directory/controller')
            ->ask();
        $model = $prompt->fetch();
        if (file_exists('app/Models/' . $model . '.php')) {
            $this->flashError($cliMenu, 'The model already exists');
        } else {
            shell_exec('touch ./app/Models/' . $model . '.php');
            $tableName = strtolower($model);
            $content = "<?php" . PHP_EOL . "namespace App\Models;" . PHP_EOL . PHP_EOL . "class $model extends Model" . PHP_EOL . "{\n\tpublic static function getTableName(): string\n\t{\n\t\treturn '$tableName';\n\t}" . PHP_EOL . PHP_EOL . "\t public static function getColumns(): array\n\t{\n\t\treturn [];//TODO: mettre les colonnes, ne pas mettre id et created_at\n\t}" . PHP_EOL . PHP_EOL . "}";
            file_put_contents('./app/Models/' . $model . '.php', $content);
            $this->flashSuccess($cliMenu, 'The Model created successfully\nCheck the file in the app/Models/' . $model . '.php');


        }
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
        }
        $this->givePerms();
    }

    private function migrate(CliMenu $cliMenu)
    {
        $models = scandir('./database/migrations');
        array_shift($models);
        array_shift($models);
        if (sizeof($models) <= 0) {
            $this->flashError($cliMenu, 'There is no migration file in the database/migrations folder');
            try {
                $cliMenu->close();
            } catch (InvalidTerminalException $e) {
                dump('Impossible to close the cli');

            }
        }
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
            dump('Impossible to close the cli');
        }
        $this->givePerms();
    }

    private function make_migration(CliMenu $cliMenu)
    {
        $prompt = $cliMenu->askText();
        $prompt->getStyle()
            ->setBg('blue')
            ->setFg('black');
        $prompt = $prompt->setPromptText('Enter the name of the migration')
            ->setPlaceholderText('')
            ->setValidationFailedText('The migration name must be only like create_user_table')
            ->ask();
        $migration = $prompt->fetch();
        if (file_exists('database/migrations/' . $migration . '.php')) {
            $this->flashError($cliMenu, 'The migration already exists');
        } else {
            $migration = "m" . date('YmdHis') . '_' . $migration;
            shell_exec('touch ./database/migrations/' . $migration . '.php');
            $content = "<?php" . PHP_EOL . PHP_EOL . "namespace Database\migrations;" . PHP_EOL . PHP_EOL . "use Core\SqlBuilder;" . PHP_EOL . PHP_EOL . "class $migration" . PHP_EOL . "{" . PHP_EOL . "\tprivate \$tableName;" . PHP_EOL . "\tprivate \$table;" . PHP_EOL . PHP_EOL . "\tpublic function __construct()\n\t{" . PHP_EOL . "\t\t\$this->tableName = ''//TODO: put table name, must be same than model name;" . PHP_EOL . "\t\t\$this->table = new SqlBuilder(\$this->tableName);" . PHP_EOL . "\t}\n\tpublic function up(){\n\t\t//TODO:Construire la structure de la table. Ne pas oublier le create au début et le endcreation à la fin et bien return le tout\n\t}" . PHP_EOL . "}";
            file_put_contents('./database/migrations/' . $migration . '.php', $content);
            $this->flashSuccess($cliMenu, 'The migration created successfully\nCheck the file in the database/migrations/' . $migration . '.php');
        }
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
        }
        $this->givePerms();
    }

    private function givePerms()
    {
        shell_exec("chmod -R 777 ./");
    }

}