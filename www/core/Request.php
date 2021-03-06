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
    public static function getBody(): array
    {
        return $_GET;
    }

    public static function filesBody(): array
    {
        return $_FILES;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? "GET";
    }

    public static function get($key)
    {
        return htmlspecialchars($_GET[$key]) ?? false;
    }

    public static function post($key)
    {
        return htmlspecialchars($_POST[$key]) ?? [];
    }

    public static function file($key)
    {
        return $_FILES[$key] ?? [];
    }

    public static function cookie($key)
    {
        return $_COOKIE[$key] ?? [];
    }



    public static function validateRules($data)
    {
        foreach ($data as $key => $value) {
            if (!isset($value['rules'])) {
                continue;
            } else {

                foreach ($value['rules'] as $rule) {
                    $rule = explode(':', $rule);
                    $ruleName = $rule[0];
                    if (count($rule) > 1) {
                       $rule[0] = $value['value'];
                        $callable = [Rules::class, $ruleName];
                        $called = call_user_func_array($callable, $rule);
                    } else {
                        $callable = [Rules::class, $ruleName];
                        $called = call_user_func($callable, $value['value']);
                    }
                    if($called === true){
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