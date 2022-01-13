<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Order
*@NameSpace: App\Models
*/

namespace App\Models;

class Order extends Model
{

    public static function getTableName(): string
    {

        return "order";
    }

    public static function getUnique(): string
    {

        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "user_id",
            "products",
            "status",
        ];
    }

    public static function toValidate(): array
    {
       return [
           "user_id","products","status"
       ];
    }
    
    public static function foreigns(): array
    {
        return [

        ];
    }
}
