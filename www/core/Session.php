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
    /*
     * Méthode générale
     * */

    public static function session($key)
    {
        return $_SESSION[$key] ?? [];
    }
    public static function clearSession($key = "")
    {
        if ($key == "") {
            unset($_SESSION);
        } else {
            unset($_SESSION[$key]);
        }
    }

    /*
     * Méthode pour ajouter des messages flash
     * */
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


    /*
     * Méthodes pour la gestion des csrf
     * */

    public static function csrf(): string
    {
        $csrfTokens = self::session('csrf');
        $token = Request::token();
        if (!empty($csrfTokens)) {
            while (in_array($token, $csrfTokens)) {
                $token = Request::token();
            }
        }
        self::setSessionCsrf($token);
        return $token;
    }

    public static function setSessionCsrf($value)
    {
        $_SESSION['csrf'][] = $value;
    }

    public static function clearCsrf($token)
    {
        $csrfTokens = self::session('csrf');
        $key = array_search($token, $csrfTokens);
        unset($csrfTokens[$key]);
        self::setSessionCsrf($csrfTokens);
    }

    /*
     * Méthode pour la gestion des validations de messages
    */
    public static function validate($datas, $values)
    {
        $errors = [];
        foreach ($datas as $key => $value) {
            if (empty($values[$key])) {
                $errors[$key] = "The field $key is required";
            }
        }
        if (!empty($errors)) {
            session("validation", $errors);
            back();
        }
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