<?php

namespace Core;


class DotEnvParser
{
    private array $configFiles;
    public function __construct($path = null)
    {
        $this->configFiles = [];
        $this->load($path);
    }



    private function load($path = null){
        if(!$path){
            $path = dirname(__DIR__)."/configFiles/";
        }
        $files = scandir($path,SORT_ASC);
        foreach ($files as $file){
            $fileExtension = explode(".",$file)[1];
            if($fileExtension === "env"){
                $this->configFiles[] = $file;
            }
        }
        if(sizeof($this->configFiles)>0){
            foreach ($this->configFiles as $file){
                $file = $path.$file;
                $content = fopen($file,"r");
                $content = fread($content,filesize($file));
                $content = explode("\n",$content);
                foreach ($content as $line){
                    $contentInfo = explode("=",$line);
                    $name = $contentInfo[0];
                    $value = $contentInfo[1];
                    $comment = str_split($name)[0];
                    $value = str_replace("\r","",$value);
                    if(!empty($value) and $comment !="#"){

                        $_SERVER[$name] = $value;
                    }
                }
            }
        }
    }
}