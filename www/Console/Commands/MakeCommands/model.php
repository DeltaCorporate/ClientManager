<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 20:08
*@Class: model
*@NameSpace: Console\Commands\MakeCommands
*/

namespace Console\Commands\MakeCommands;

use Console\Commands\Utils;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class model extends Utils
{
    public function create(CliMenu $cliMenu)
    {

        $model = $this->ask($cliMenu, 'Enter the name of the model', 'The Model name must be only with model name. Directories are not allowed');
        if (file_exists('app/Models/' . $model . '.php')) {
            $this->flashError($cliMenu, 'The model already exists');
        } else {
            shell_exec("curl https://raw.githubusercontent.com/DeltaCorporate/frametemplates/main/commands/makecommands/model.php -o app/Models/" . $model . ".php");
            $content = file_get_contents('app/Models/' . $model . '.php');
            $content = str_replace('TABLENAME', strtolower($model), $content);
            $content = str_replace('NAME', $model, $content);
            file_put_contents('app/Models/' . $model . '.php', $content);
            $this->flashSuccess($cliMenu, 'The Model created successfully\nCheck the file in the app/Models/' . $model . '.php');
        }
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
        }
        $this->givePerms();
    }
}