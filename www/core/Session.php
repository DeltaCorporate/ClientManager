<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 22:28
*@Class: Session
*@NameSpace: Core
*/

namespace Core;

class Session
{
    protected const FLASH_KEY = 'flash';

    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        $flashMessages = array_map(function ($message) {
            $message['remove'] = true;
            return $message;
        }, $flashMessages);
        Session::setFlash($flashMessages);

    }





    public static function setFlash($value)
    {
        $_SESSION[self::FLASH_KEY] = $value;
    }




    public static function getFlash($key)
    {
        if (isset($_SESSION[self::FLASH_KEY][$key])) {
            return $_SESSION[self::FLASH_KEY][$key];
        }
        return [];
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            dump($flashMessage);
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        Session::setFlash($flashMessages);
    }

}