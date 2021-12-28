<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:31
*/

use Console\Commands;
use Core\DotEnvParser;
use Database\Database;

require "vendor/autoload.php";

new DotEnvParser("./configFiles/");
Database::connection();
new Commands();








