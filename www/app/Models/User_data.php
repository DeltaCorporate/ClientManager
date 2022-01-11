<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: user_data
*@NameSpace: App\Models
*/

namespace App\Models;

class User_data extends Model
{

    public static function getTableName(): string
    {
        return "user_data";
    }

    public static function getUnique(): string
    {
        return "user_id";
    }

    public static function getColumns(): array
    {
        return [
            "user_id",
            "firstname",
            "lastname",
            "telephone",
            "address",
            "roles",
            "avatar"
        ];
    }

    public static function toValidate(): array
    {
       return [
          "user_id",
          "firstname",
          "lastname",
          "telephone",
          "address",
          "roles",
          "avatar"
       ];
    }

    public static function find($id)
    {
        $self = new static();
        $table = $self->getTableName();
        $primaryKey = $self->primaryKey();
        $sql = "SELECT * FROM `$table` WHERE $primaryKey = :" . $primaryKey;
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":" . $primaryKey, $id);
        $stmt->execute();
        $associations = $self->foreigns();
        $result = $stmt->fetchObject(static::class);
        $result = self::hydrate($result, $associations,$id);
        $rolesBrute = $result->roles;
         $rolesBrute = json_decode($rolesBrute);
         $roles = [];
         foreach ($rolesBrute as $role) {
             $roles[] = Role::find($role);
         }
         $result->roles = $roles;
        return $result;

    }

    public static function findBy($column, $value)
    {
        $self = new static();
        $table = $self->getTableName();
        $sql = "SELECT * FROM `$table` WHERE $column = :$column";
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        $associations = $self->foreigns();
        $result = $stmt->fetchObject(static::class);
        $result = self::hydrate($result, $associations);
        $rolesBrute = $result->roles;
        $rolesBrute = json_decode($rolesBrute);
        $roles = [];
        foreach ($rolesBrute as $role) {
            $roles[] = Role::find($role);
        }
        $result->roles = $roles;
        return $result;
    }


    public static function foreigns(): array
    {
        return [
//            "user_id" => [User::class,"belongsTo"]
        ];
    }
}
