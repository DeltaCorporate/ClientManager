<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 21:07
*@Class: seeders
*@NameSpace: Console\Commands\SeedersCommands
*/

namespace Console\Commands\SeedersCommands;

use Console\Commands\Utils;
use Database\seeders\Seeder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class seeders extends Utils
{
    public function make(CliMenu $cliMenu)
    {
        $seeder = $this->ask($cliMenu, 'Enter the name of the seeder', 'The seeder name must be only with seeder name. Directories are not allowed');
        if (file_exists('database/seeders/' . $seeder . 'Seeder.php')) {
            $this->flashError($cliMenu, 'The seeder already exists');
        } else {
            shell_exec("curl https://raw.githubusercontent.com/DeltaCorporate/frametemplates/main/commands/makecommands/seeder.php -o database/seeders/" . $seeder . "Seeder.php");
            $content = file_get_contents('database/seeders/' . $seeder . 'Seeder.php');
            $content = str_replace('NAME', $seeder, $content);
            file_put_contents('database/seeders/' . $seeder . 'Seeder.php', $content);
            $this->flashSuccess($cliMenu, 'The seeded created successfully\nCheck the file in the database/seeders/' . $seeder . 'Seeder.php');
        }
        try {
            $cliMenu->close();
        } catch (InvalidTerminalException $e) {
            dump('Impossible to close the cli');
        }
        $this->givePerms();
    }
    public function run(CliMenu $cliMenu)
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

}