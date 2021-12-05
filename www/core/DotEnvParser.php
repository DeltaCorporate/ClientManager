<?php

namespace Core;


class DotEnvParser
{
    private array $configFiles;
    public function __construct()
    {
        $this->configFiles = [];
        $this->load();
    }



    private function load(){
        $files = scandir("../configFiles",SORT_ASC);
        foreach ($files as $file){
            $fileExtension = explode(".",$file)[1];
            if($fileExtension === "env"){
                $this->configFiles[] = $file;
            }
        }
        if(sizeof($this->configFiles)>0){
            foreach ($this->configFiles as $file){
                $file = "../configFiles/".$file;
                $content = fopen($file,"r");
                $content = fread($content,filesize($file));
                $content = explode("\n",$content);
                foreach ($content as $line){
                    $contentInfo = explode("=",$line);
                    $name = $contentInfo[0];
                    $value = $contentInfo[1];
                    $comment = str_split($name)[0];
                    if(!empty($value) and $comment !="#"){

                        $_SERVER[$name] = $value;
                    }
                }
            }
        }
    }
}