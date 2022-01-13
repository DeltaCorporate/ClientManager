<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AccountVerifToken
*@NameSpace: App\Models
*/

namespace App\Models;

class AccountVerifToken extends Model
{

    public static function getTableName(): string
    {

        return "account_verif_token";
    }

    public static function getUnique(): string
    {
        return "user_id";
    }

    public static function getColumns(): array
    {
        return [
            "token", "user_id"
        ];
    }

    public static function toValidate(): array
    {
        return [
            "token", "user_id"
        ];
    }

    public static function foreigns(): array
    {
        return [];
    }

    public static function checkIfTokenExists($token): bool
    {
        $find = self::findBy("token", $token);

        if (!$find) return false;
        return true;

    }
}
