<?php

namespace App\Models;

use Database\Database;

abstract class Model extends Database
{

    abstract public static function getTableName(): string;

    abstract public static function getColumns(): array;

    public function primaryKey(): string
    {
        return 'id';
    }

    public static function bulkDelete(): bool
    {
        $table = static::getTableName();
        $sql = "DELETE FROM $table";
        $stmt = static::$db->prepare($sql);
        $stmt->execute();
        return true;
    }
    public static function delete($id): bool
    {
        $self = new static();
        $table = $self->getTableName();
        $primaryKey = $self->primaryKey();
        $sql = "DELETE FROM `$table` WHERE $primaryKey = :" . $primaryKey;
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":" . $primaryKey, $id);
        $stmt->execute();
        return true;
    }
    public static function save($values): bool
    {
        $self = new static();
        $tableName = $self->getTableName();
        $columns = $self->getColumns();
        $params = array_map(fn($attr) => ':' . $attr, $columns);
        $sql = "INSERT INTO $tableName (" . implode(",", $columns) . ") VALUES (" . implode(",", $params) . ")";
        $statement = self::$instance->prepare($sql);
        foreach ($columns as $column) {
            $statement->bindValue(':' . $column, $values[$column]);
        }
        $statement->execute();
        return true;
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
        return $stmt->fetchObject(static::class);
    }

    public static function findAll()
    {
        $self = new static();

        $table = $self->getTableName();
        $sql = "SELECT * FROM " . $table;
        $stmt = static::$instance->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}

