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

    public function flush(): array{
        return $this->user;
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


    public function setUsername(string $pseudo): User
    {
        $this->user['username'] = $pseudo;
        return $this;
    }

    public function setEmail(string $email): User
    {
        $this->user['email'] = $email;

        return $this;
    }

    public function setPassword(string $password) :User
    {
        $this->user['password'] = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->user['email'];
    }

    public function getPassword(){
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