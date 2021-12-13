<?php

namespace App\Models;

use Core\Session;

class User extends Model
{
    private $user;

    public function __construct($user = null)
    {
        if (is_null($user)) {
            $this->user = [];;
        } else {
            $this->user = $user;
        }
    }

    public static function getTableName(): string
    {
        return 'user';
    }

    public static function getColumnsToLogin(): array
    {
        return ['email', 'password'];
    }

    public static function getColumns(): array
    {
        return [
            'username',
            'email',
            'password',
        ];
    }

    public static function getNotMappedColumns(): array
    {
        return [
            "password_confirm",
        ];
    }

    public static function getUnique(): string
    {
        return 'email';
    }


    public function setUsername(string $pseudo)
    {
        $this->user['username'] = $pseudo;

    }

    public function setEmail(string $email)
    {
        $this->user['email'] = $email;

    }

    public function setPassword(string $password)
    {
        $this->user['password'] = $password;

    }

    public function getEmail()
    {
        return $this->user['email'];
    }

    public function getPassword()
    {
        return $this->user['password'];
    }

    public static function checkPasswordConfirm(string $password, string $password_confirm): bool
    {
        if ($password !== $password_confirm) {
            Session::validation("password_confirm", "Les mots de passe ne correspondent pas");
            back();
        }
        return true;
    }

    public function getUser(): array
    {
        return $this->user;
    }


}