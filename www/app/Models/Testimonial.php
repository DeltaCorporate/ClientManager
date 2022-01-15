<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Testimonial
*@NameSpace: App\Models
*/

namespace App\Models;

class Testimonial extends Model
{

    public static function getTableName(): string
    {
        return "testimonial";
    }

    public static function getUnique(): string
    {

        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "user_id","product_id",'comment'
        ];
    }

    public static function toValidate(): array
    {
       return [
           "user_id","product_id",'comment'
       ];
    }
    
    public static function foreigns(): array
    {
        return [
            "user_id" => [User::class, "belongsTo"],
            "product_id" => [Product::class, "belongsTo"]
        ];
    }
}
