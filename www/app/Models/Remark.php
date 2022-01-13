<?php

namespace App\Models;

class Remark extends Model
{
    public static function getTableName() : string
    {
        return "remark";
    }

    public static function getUnique(): string
    {
        return "id";
    }

    public static function getColumns(): array
    {
        return [
            "id", "user_name", "id_product", "txt", "date"
        ];
    }

    public static function toValidate(): array
    {
        return [
            /* Je sais pas si c'est nécessaire
              "id", "id_user", "id_product", "txt", "date"
            */
        ];
    }

    public static function foreigns(): array
    {
        return [];
    }
}