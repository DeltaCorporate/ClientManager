<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 20:25
*@Class: migration
*@NameSpace: Console\Commands\MigrationCommands
*/

namespace Console\Commands\MigrationCommands;

use Console\Commands\Utils;
use Database\Database;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class migration extends Utils
{

    public function migrate(CliMenu $cliMenu)
    {
        $instance = Database::getInstance();
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
            $migrationStatus = $instance->prepare($sql)->execute();
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

}