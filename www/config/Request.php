<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:01
*@Class: Request
*@NameSpace: Config
*/

namespace Config;

class Request
{

    public function getPath(){
        $path =  $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, '?');
       if($position == false){
            return $path;
        }
       return substr($path, 0, $position);
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? "GET";
    }

    public static function get($key){
        return $_GET[$key] ?? null;
    }
    public static function post($key){
        return $_GET[$key] ?? null;
    }
    public static function file($key){
        return $_FILES[$key] ?? null;
    }
    public static function cookie($key){
        return $_COOKIE[$key] ?? null;
    }
    public static function session($key){
        return $_SESSION[$key] ?? null;
    }



}