<?php

namespace App\Models;

class PasswordReset extends Model
{
    private array $passwordReset;

    public function __construct()
    {
        $this->passwordReset = [];
    }

    public function getPasswordReset(): array
    {
        return $this->passwordReset;
    }

    public function setUser($userID)
    {
        $this->passwordReset["user_id"] = $userID;
    }

    public function setToken($token)
    {
        $this->passwordReset["token"] = $token;
    }


    public static function getTableName(): string
    {
        return 'passwordreset';
    }

    public static function getColumns(): array
    {
        return [
            'user_id',
            'token',
        ];
    }

    public static function getUnique(): string
    {
        return 'token';
    }

    public static function checkIfTokenExists($token): bool
    {
        $passwordReset = self::findBy('token', $token);
        if ($passwordReset) {
            return true;
        }
        return false;
    }


    public static function hasUser($val)
    {
        return self::belongsTo(User::class, $val);
    }

    public static function toValidate(): array
    {
        return [
            "email"
        ];
    }

    public static function foreigns(): array
    {
        return [];
    }
}