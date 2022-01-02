<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 08/12/2021 at 21:51
*@Class: Rules
*@NameSpace: Core
*/

namespace Core;

class Rules
{
    public function required($value)
    {
        if(empty($value))
        {
            return ":key field is required";
        }
        return true;
    }
    public function int($value)
    {
        if (!is_numeric($value)) {
            return ":key must be a number";
        }
        return true;
    }

    public function image($value){
        $allowed_extensions = array( 'jpg' , 'jpeg' , 'svg' , 'png' );
        $extension = strtolower( substr( strrchr($value['name'], '.') ,1) ) ;
        if(!is_file($value['tmp_name']) or !in_array($extension, $allowed_extensions)){
            return ":key must be an image. Only jpg, jpeg, svg and png formats are allowed";
        }
        return true;
    }

    public function string($value)
    {
        if (!is_string($value) or is_numeric($value)) {
            return ":key must be a string";
        }
        return true;
    }

    public function float($value)
    {
        if (!is_float($value)) {
            return ":key must be a float";
        }
        return true;
    }

    public function bool($value)
    {
        if (!is_bool($value)) {
            return ":key must be a boolean";
        }
        return true;
    }

    public function array($value)
    {
        if (!is_array($value)) {
            return ":key must be an array";
        }
        return true;
    }

    public function length($value, $min = 6, $max = 8)
    {
        if (strlen($value) < $min or strlen($value) > $max) {
            return ":key length must be between $min and $max characters";
        }
        return true;
    }

    public function email($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return ":key must be a valid email";
        }
        return true;
    }

    public function url($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return ":key must be a valid url";
        }
        return true;
    }

    public function regex($value, $regex)
    {
        if (!preg_match($regex, $value)) {
            return ":key must match the regex";
        }
        return true;
    }

}