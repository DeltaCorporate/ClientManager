<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Category
*@NameSpace: App\Models
*/

namespace App\Models;

class Category extends Model
{

    public static function getTableName(): string
    {
        return "category";
    }

    public static function getUnique(): string
    {
        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "name","description"
        ];
    }

    public static function toValidate(): array
    {
       return [
           // TODO: Put here the values to validate
       ];
    }
}
