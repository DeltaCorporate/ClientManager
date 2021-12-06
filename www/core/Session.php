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
    protected static $flash;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        self::$flash = Request::session('flash') ?? [];
        $this->updateFlashToRemove();

    }

    public function updateFlashToRemove()
    {

        $arr2 = array_map(fn($message) =>{
            return [
                "remove" => true,
                "value" =>
    ];
        });
        $flash = Request::session("flash");
        foreach ($flash as $key => $message) {
            $message["remove"] = true;
        }
        $this->setFlash('flash', self::$flash);
    }


    public static function setFlash($key, $value)
    {
        $_SESSION['flash'][$key] = [
            "remove" => false,
            "value" => $value
        ];
    }

    public static function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            return $_SESSION['flash'][$key];
        }
        return [];
    }

    public function __destruct()
    {
        $flash = Request::session('flash');
        foreach ($flash as $key => $message) {

            if ($message['remove']) {
                unset($flash[$key]);
            }
        }
        $this->setFlash('flash', $flash);
    }

}