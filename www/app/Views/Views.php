<?php

namespace App\Views;

class Views
{

   static function dd($content){
        if(empty($content)){
            $content = "null variable";
        }
        echo "<pre style='color: greenyellow;background:black;padding: 5px 0 5px 10px;margin: 0'>";
        var_dump($content);
        echo "</pre>";
        die();
    }

}