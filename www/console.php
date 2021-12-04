<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 08:31
*/

use Console\Commands;
use Database\Database;

require "vendor/autoload.php";

Database::connection(
    [
        "host" => "progDatabase",
        "username" => "root",
        "password" => "root",
        "dbname" => "client"
    ]
);
new Commands();






