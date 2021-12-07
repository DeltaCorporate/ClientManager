<?php

namespace App\Models;

class User extends Model
{

    public static function getTableName(): string
    {
       return 'user';
    }

    public static function getColumns(): array
    {
        return [
            'name',
            'email',
            'password',
        ];
    }
}