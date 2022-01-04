<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Product
*@NameSpace: App\Models
*/

namespace App\Models;

class Product extends Model
{

    public static function getTableName(): string
    {
        return "product";
    }

    public static function getUnique(): string
    {
        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "name", "description", "price", "quantity", "category_id"
        ];
    }

    public static function toValidate(): array
    {
        return [
            // TODO: Put here the values to validate
        ];
    }
}
