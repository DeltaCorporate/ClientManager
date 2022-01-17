<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: Review
*@NameSpace: App\Models
*/

namespace App\Models;

class Review extends Model
{

    public static function getTableName(): string
    {

        return "review";
    }

    public static function getUnique(): string
    {
        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "user_id","testimonial_id","review"
        ];
    }

    public static function toValidate(): array
    {
       return [
           "user_id","testimonial_id","review"
       ];
    }
    
    public static function foreigns(): array
    {
        return [
            "user_id"=>[User::class,"belongsTo"],
            "testimonial_id"=>[Testimonial::class,"belongsTo"]
        ];
    }
}
