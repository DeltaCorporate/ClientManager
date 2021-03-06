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
            'verified',
        ];
    }

    public static function hasToken($val){
        self::hasOneToOne(PasswordReset::class, $val);
    }

    public function hasData($val){
        self::hasOneToOne(User_data::class, $val);
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
            Session::validation("password_confirm", "Password confirmation doesn't match");
            back();
        }
        return true;
    }

    public function getUser(): array
    {
        return $this->user;
    }


    public static function toValidate(): array
    {
        return [
            'username',
            'email',
            'password',
            'password_confirm'
        ];
    }

    public static function hasRole($user,$roleTofind): bool
    {
     $roles = $user->data->roles;
        foreach ($roles as $role) {
            if ($role->name == $roleTofind) {
                return true;
            }
        }
        return false;
    }

    public static function foreigns(): array
    {
        return [
            "data"=>[User_data::class,"hasOneToOne"],
            "resetToken"=>[PasswordReset::class,"hasOneToOne"],
            "verifyToken"=>[AccountVerifToken::class,"hasOneToOne"],
            "orders"=>[Order::class,'hasOneToMany'],
            "Testimonials"=>[Testimonial::class,'hasOneToMany'],
        ];
    }
}