<?php

namespace App\Models;

use App\Exceptions\ModelColumnNotfound;
use Core\Session;
use Database\Database;

abstract class Model extends Database
{


    abstract public static function getTableName(): string;

    abstract public static function getUnique(): string;

    abstract public static function getColumns(): array;

    abstract public static function getNotMappedColumns(): array;

    public function primaryKey(): string
    {
        return 'id';
    }

    public static function checkIfUniqueRespected($value): bool
    {
        $self = new static();
        $uniqueField = $self->getUnique();
        if (!empty($uniqueField)) {
            $tableName = $self->getTableName();
            $sql = "SELECT * FROM `$tableName` WHERE $uniqueField = :$uniqueField";
            $stmt = self::$instance->prepare($sql);
            $stmt->bindValue(":$uniqueField", $value);
            $stmt->execute();
            $result = $stmt->fetch();
            if (!empty($result)) {
                flash("error", "This $uniqueField already exists");
                back();
            }
        }
        return false;
    }
//dowohufudi@mailinator.com
    public static function bulkDelete(): bool
    {
        $self = new static();
        $table = $self->getTableName();
        $sql = "DELETE FROM `$table`";
        $stmt = static::$instance->prepare($sql);
        $stmt->execute();
        return true;
    }

    public static function matchPostValuesToValidationData($session,$rules =[],$columns = null,$columsNotMapped = true): array
    {
        $self = new static();
        if(is_null($columns)){
            $columns = $self->getColumns();
        }
        $values = [];
        foreach ($columns as $column) {
            if (!isset($session[$column]) or empty($session[$column])) {
                Session::validation($column, "The $column field is required");
                back();
            }
            $values[$column]['value'] = htmlspecialchars($session[$column]);
            if(isset($rules[$column])){
                $values[$column]['rules'] = $rules[$column];
            }
        }
        if($columsNotMapped){
            $notMappedColumns = $self->getNotMappedColumns();
            foreach ($notMappedColumns as $column) {
                if (!isset($session[$column]) or empty($session[$column])) {
                    Session::validation($column, "The $column field is required");
                    back();
                }
                $values[$column]['value'] = htmlspecialchars($session[$column]);
                if(isset($rules[$column])){
                    $values[$column]['rules'] = $rules[$column];
                }
            }
        }
        return $values;
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

    /**
     * @throws ModelColumnNotfound
     */
    public static function update($id, $data): bool
    {
        $self = new static();
        $table = $self->getTableName();
        $primaryKey = $self->primaryKey();
        $sql = "UPDATE `$table` SET ";
        foreach ($data as $key => $value) {
            $sql .= "`$key` = :$key, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE $primaryKey = :$primaryKey";
        $stmt = self::$instance->prepare($sql);
        foreach ($data as $key => $value) {
            if ($self->existColumn($key)) {
                $stmt->bindValue(":" . $key, $value);
            }
        }
        $stmt->bindValue(":" . $primaryKey, $id);
        $stmt->execute();
        return true;
    }

    /**
     * @throws ModelColumnNotfound
     */
    protected function existColumn($column): bool
    {
        $self = new static();
        $table = $self->getTableName();
        if (!in_array($column, $self->getColumns())) {
            throw new ModelColumnNotfound("Column $column not found in table $table");

        }
        return true;
    }

    /**
     * @throws ModelColumnNotfound
     */
    public static function save($values): bool
    {
        $self = new static();
        $tableName = $self->getTableName();
        $columns = $self->getColumns();
        $params = array_map(fn($attr) => ':' . $attr, $columns);
        $sql = "INSERT INTO $tableName (" . implode(",", $columns) . ") VALUES (" . implode(",", $params) . ")";
        $statement = self::$instance->prepare($sql);
        foreach ($columns as $key => $column) {
            if ($self->existColumn($column)) {
                $statement->bindValue(':' . $column, $values[$column]);
            }
        }
        $statement->execute();
        return true;
    }

    /**
     * @throws ModelColumnNotfound
     */
    public static function bulkCreate($datas)
    {
        foreach ($datas as $data) {
            static::save($data);
        }
    }

    /**
     * @throws ModelColumnNotfound
     */
    public static function bulkUpdate($datas)
    {
        foreach ($datas as $data) {
            static::update($data['id'], $data['values']);
        }
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

    public static function findBy($colomn, $value)
    {
        $self = new static();
        $table = $self->getTableName();
        $sql = "SELECT * FROM `$table` WHERE $colomn = :$colomn";
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":" . $colomn, $value);
        $stmt->execute();
        return $stmt->fetch();
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

