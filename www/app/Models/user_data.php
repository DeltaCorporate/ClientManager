<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: user_data
*@NameSpace: App\Models
*/

namespace App\Models;

class user_data extends Model
{

    public static function getTableName(): string
    {
        // TODO: PUT the table name here
        return "user_data";
    }

    public static function getUnique(): string
    {
        // TODO: put the unique colmn here
        return "";
    }

    public static function getColumns(): array
    {
        return [
            // TODO: put here the columns of the table
        ];
    }

    public static function toValidate(): array
    {
       return [
           // TODO: Put here the values to validate
       ];
    }
}
