<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:31
*/

use Console\Commands;

require "vendor/autoload.php";

\Database\Database::connection([
    'host' => 'progDatabase',
    'dbname' => 'client',
    'username' => 'root',
    'password' => 'root',
]);
new Commands();








