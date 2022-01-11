<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Role
*@NameSpace: App\Models
*/

namespace App\Models;

class Role extends Model
{

    public static function getTableName(): string
    {
        return "role";
    }

    public static function getUnique(): string
    {

        return "name";
    }

    public static function getColumns(): array
    {
        return [
            "name", "description"
        ];
    }

    public static function toValidate(): array
    {
        return [
            "name", "description"
        ];
    }

    public static function foreigns(): array
    {
        return [
        ];
    }
}
