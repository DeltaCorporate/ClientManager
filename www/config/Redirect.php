<?php

namespace Config;

class Redirect
{


    public static function redirect($name,$method="get",$datas=[]){
        $link = Router::generateURL($name,$method,$datas);

        if($link !==""){
            header('Location: '.$link);
        } else{
            header('Location: '.$_SERVER['HOME']);
        }
        exit();
    }




}