<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProductImages
*@NameSpace: App\Models
*/

namespace App\Models;

class ProductImages extends Model
{

    public static function getTableName(): string
    {
        // TODO: PUT the table name here
        return "product_images";
    }

    public static function getUnique(): string
    {
        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "product_id",
            "image",
        ];
    }

    public static function toValidate(): array
    {
       return [
           // TODO: Put here the values to validate
       ];
    }
    
    public static function foreigns(): array
    {
        return [
            "product_id"=>[Product::class,"belongsTo"]
        ];
    }
}
