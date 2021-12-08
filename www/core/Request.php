<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:01
*@Class: Request
*@NameSpace: Config
*/

namespace Core;

class Request
{

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? "/";
        $position = strpos($path, '?');
        if ($position == false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public static function postBody(): array
    {
        return $_POST;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? "GET";
    }

    public static function get($key)
    {
        return $_GET[$key] ?? [];
    }

    public static function post($key)
    {
        return $_GET[$key] ?? [];
    }

    public static function file($key)
    {
        return $_FILES[$key] ?? [];
    }

    public static function cookie($key)
    {
        return $_COOKIE[$key] ?? [];
    }

    public static function token(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 50; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public static function validateRules($data){
        foreach ($data as $key => $value){
            if(!isset($value['rules'])){
                continue;
            } else{
                foreach ($value['rules'] as $rule){
                    $called = call_user_func_array($rule, [$value['value']]);
                    if($called == true){
                        continue;
                    } else{
                        $called = str_replace(':key', $key, $called);
                        Session::validation($key, $called);
                        back();
                    }
                }
            }
        }
    }


}