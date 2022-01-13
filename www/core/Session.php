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
    protected const VALIDATION = 'validation';

    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        $flashMessages = array_map(function ($message) {
            $message['remove'] = true;
            return $message;
        }, $flashMessages);
        Session::setFlash($flashMessages);

        $validationMessages = $_SESSION[self::VALIDATION] ?? [];

        $validationMessages = array_map(function ($message) {
            $message['remove'] = true;
            return $message;
        }, $validationMessages);
        Session::setValidation($validationMessages);
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

    public static function flash($key, $value)
    {
        $_SESSION[self::FLASH_KEY][$key] = $value;
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

    public static function setSessionCsrf($csrf)
    {
        $_SESSION['csrf'][] = $csrf;
    }


    /*
     * Méthode pour la gestion des validations de messages
    */
    public static function validation($key, $value)
    {
        $_SESSION[self::VALIDATION][$key] = [
            'value' => $value,
            'remove' => false
        ];
    }
    public static function setSession($key,$val){
        $_SESSION[$key] = $val;
    }

    private static function setValidation($validationMessages)
    {
        $_SESSION[self::VALIDATION] = $validationMessages;
    }

    public static function removeValidationMessageAuto(): void
    {
        foreach ($_SESSION[self::VALIDATION] as $key => $value) {
            if ($value['remove']) {
                unset($_SESSION[self::VALIDATION][$key]);
            }
        }
    }

    public static function removeFlashMessageAuto()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        Session::setFlash($flashMessages);
    }

    public static function removeExpiredCsrfs()
    {
        $tokens = self::session('csrf');
        foreach ($tokens as $key => $csrf) {
            if(time()>=$csrf->getExpireAt()){
                unset($tokens[$key]);
            }
        }
        Session::setSession('csrf',$tokens);
    }

    /*
     * GESTION UTILISATEUR
     */

    public static function setUser($user){
        $_SESSION['user'] = $user;
    }

    public static function getUser(){
        return $_SESSION['user'] ?? null;
    }

    public static function removeUser(){
        unset($_SESSION['user']);
    }

    public function __destruct()
    {
        self::removeFlashMessageAuto();
        self::removeValidationMessageAuto();
        self::removeExpiredCsrfs();
    }

}