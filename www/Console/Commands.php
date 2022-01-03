<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:44
*@Class: Commands
*@NameSpace: ${Console}
*/

namespace Console;

use Console\Commands\ControllersCommands\controllers;
use Console\Commands\MiddlewaresCommands\middleware;
use Console\Commands\MigrationCommands\migration;
use Console\Commands\ModelsCommands\model;
use Console\Commands\SeedersCommands\seeders;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;


class Commands
{
    public function __construct()
    {
        $this->menu();
        $this->givePerms();
    }

    private function menu()
    {
        try {
            (new CliMenuBuilder)
                ->setTitle('Client Manager Console')
                ->setTitleSeparator('#')
                ->addItem('make:controller', function (CliMenu $cliMenu) {
                    call_user_func([new controllers(), 'make'], $cliMenu);
                })
                ->addItem("make:model", function (CliMenu $cliMenu) {
                    call_user_func([new model(), 'make'], $cliMenu);
                })
                ->addItem("make:migration", function (CliMenu $cliMenu) {
                    call_user_func([new migration(), 'make'], $cliMenu);
                })
                ->addItem("make:middleware", function (CliMenu $cliMenu) {
                    call_user_func([new middleware(), "make"], $cliMenu);
                })
                ->addItem("run:migration", function (CliMenu $cliMenu) {
                    call_user_func([new migration(), "migrate"], $cliMenu);
                })
                ->addItem("run:seeder", function (CliMenu $cliMenu) {
                    call_user_func([new seeders(), "run"], $cliMenu);
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