<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 13:42
*@Class: UndefinedOptionsException
*@NameSpace: App\Exceptions
*/

namespace App\Exceptions;

use Exception;

class RouteNotFound extends Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}