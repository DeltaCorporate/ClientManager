<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: user_data
*@NameSpace: App\Models
*/

namespace App\Models;

class User_data extends Model
{

    public static function getTableName(): string
    {
        return "user_data";
    }

    public static function getUnique(): string
    {
        return "user_id";
    }

    public static function getColumns(): array
    {
        return [
            "user_id",
            "firstname",
            "lastname",
            "telephone",
            "address",
            "roles",
            "avatar"
        ];
    }

    public static function toValidate(): array
    {
       return [
          "user_id",
          "firstname",
          "lastname",
          "telephone",
          "address",
          "roles",
          "avatar"
       ];
    }

    public function hasUser($val){
        return $this->belongsTo(User::class, $val);
    }

    public static function foreigns(): array
    {
        return [];
    }
}
