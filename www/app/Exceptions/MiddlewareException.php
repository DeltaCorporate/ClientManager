<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 26/12/2021 at 09:20
*@Class: MiddlewareException
*@NameSpace: App\Exceptions
*/

namespace App\Exceptions;

use Exception;
use Throwable;

class MiddlewareException extends Exception
{
    protected $code = 404;
    protected $message = 'Middleware not found';
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        return render("errors.exception", ["message" => $message]);
    }
}