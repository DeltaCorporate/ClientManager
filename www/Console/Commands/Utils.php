<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 28/12/2021 at 19:26
*@Class: Utils
*@NameSpace: Console\Commands
*/

namespace Console\Commands;

use PhpSchool\CliMenu\CliMenu;

abstract class Utils
{

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
    public function ask(CliMenu $cliMenu,$msg,$errmsg): string
    {
        $result = $cliMenu->askText();
        $result->getStyle()
            ->setBg('blue')
            ->setFg('black');
        $result = $result->setPromptText($msg)
            ->setPlaceholderText('')
            ->setValidationFailedText($errmsg)
            ->ask();
        return $result->fetch();
    }
    public function givePerms()
    {
        shell_exec("chmod -R 777 ./");
    }
}