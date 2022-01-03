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