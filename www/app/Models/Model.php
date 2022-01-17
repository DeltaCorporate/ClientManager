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

    abstract public static function toValidate(): array;

    abstract public static function foreigns(): array;

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

    public static function associateRulesAndDatas($datas, $rules = [], $columns = null): array
    {
        $self = new static();
        if (is_null($columns)) {
            $columns = $self->toValidate();
        }
        $values = [];
        foreach ($columns as $column) {
            if (!isset($datas[$column]) or empty($datas[$column])) {
                Session::validation($column, "The $column field is required");
                back();
            }
            $values[$column]['value'] = is_string($datas[$column]) ? htmlspecialchars($datas[$column]) : $datas[$column];
            if (isset($rules[$column])) {
                $values[$column]['rules'] = $rules[$column];
            }
        }
        return $values;
    }

    public static function hasOneToOne($model, $foreignVal)
    {
        $model = new $model();
        $self = new static();
        $foreignKey = $self::getTableName() . '_id';
        return $model->findBy($foreignKey, $foreignVal);
    }

    public static function hasOneToMany($model, $foreignVal)
    {
        $model = new $model();
        $self = new static();
        $foreignKey = $self::getTableName() . '_id';
        return $model->findAllBy($foreignKey, $foreignVal);
    }

    public static function belongsTo($model, $val)
    {
        $model = new $model();
        $self = new static();
        $table = $model->getTableName(); //user
        $actualTable = $self->getTableName();//resetpassword
        $foreignKey = $table . '_id';
        $tableColumns = $model->getColumns();
        array_unshift($tableColumns, "id");
        $tableColumns = array_map(function ($column) use ($table, $actualTable) {
            return $table . '.' . $column;
        }, $tableColumns);

        $columns = implode(', ', $tableColumns);

        $sql = "SELECT $columns FROM $table INNER JOIN $actualTable ON $table.id = $actualTable.$foreignKey WHERE $table.id = :id;";
//        dd($sql);
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":id", $val);
        $stmt->execute();
        return $stmt->fetch();
    }


    public static function countBy($col, $val)
    {
        $self = new static();
        $table = $self->getTableName();
        $primaryKey = $self->primaryKey();
        $sql = "SELECT COUNT(*) as total FROM `$table` WHERE $col = :" . $col;
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":" . $col, $val);
        $stmt->execute();
        return $stmt->fetch();
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

    public static function deleteBy($key, $val): bool
    {
        $self = new static();
        $table = $self->getTableName();
        $sql = "DELETE FROM `$table` WHERE $key = :$key";
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":$key", $val);
        $stmt->execute();
        return true;
    }

    /**
     * @throws ModelColumnNotfound
     */
    public static function update(int $keyValue, array $data, string $keyColumn = "id"): bool
    {
        $self = new static();
        $table = $self->getTableName();
        $sql = "UPDATE `$table` SET ";
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE $keyColumn = :$keyColumn";
        $stmt = self::$instance->prepare($sql);
        foreach ($data as $key => $value) {
            if ($self->existColumn($key)) {
                $stmt->bindValue(":" . $key, $value);
            }
        }
        $stmt->bindValue(":" . $keyColumn, $keyValue);
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
        $sql = "INSERT INTO `$tableName` (" . implode(",", $columns) . ") VALUES (" . implode(",", $params) . ");";
        $statement = self::$instance->prepare($sql);
        foreach ($columns as $column) {
            if ($self->existColumn($column)) {
                $value = $values[$column];
                $statement->bindValue($column, $value);
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
        $associations = $self->foreigns();
        $result = $stmt->fetchObject(static::class);
        return self::hydrate($result, $associations, $id);


    }

    public static function hydrate($result, $associations)
    {
        if ($result) {
            $id = $result->id;
            if (!empty($associations)) {
                foreach ($associations as $foreignKey => $association) {
                    $model = $association[0];
                    $model = new $model();
                    $table = $model->getTableName();
                    $foreignVal = $result->$foreignKey;
                    unset($result->$foreignKey);
                    switch ($association[1]) {
                        case "belongsTo":
                            $result->$table = call_user_func_array([static::class, $association[1]], [$model, $foreignVal]);
                            break;
                        case "hasOneToOne":
                        case "hasOneToMany":
                            $result->$foreignKey = call_user_func_array([static::class, $association[1]], [$association[0], $id]);
                            break;
                    }
                }
            }
        }
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
        return self::hydrate($result, $associations);

    }

    public static function findByandBy($conditions)
    {
        $condition = "";
        $i = 0;
        foreach ($conditions as $column => $value) {
            $i==0 ? $condition .= "$column = :$column" :  $condition .= " AND $column = :$column";
            $i++;
        }
        $self = new static();
        $table = $self->getTableName();
        $sql = "SELECT * FROM `$table` WHERE $condition";
        $stmt = self::$instance->prepare($sql);
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":" . $column, $value);
        }
        $stmt->execute();
        $associations = $self->foreigns();
        $result = $stmt->fetchObject(static::class);
        return self::hydrate($result, $associations);

    }

    public static function findAll()
    {
        $self = new static();

        $table = $self->getTableName();
        $sql = "SELECT * FROM " . $table;
        $stmt = static::$instance->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $associations = $self->foreigns();
        foreach ($results as $key => $result) {
            $results[$key] = self::hydrate($result, $associations, $result->id);
        }
        return $results;
    }

    public static function findAllby($column, $value)
    {
        $self = new static();

        $table = $self->getTableName();
        $sql = "SELECT * FROM `$table` WHERE $column = :$column";
        $stmt = self::$instance->prepare($sql);
        $stmt->bindValue(":$column", $value);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $associations = $self->foreigns();
        foreach ($results as $key => $result) {
            $results[$key] = self::hydrate($result, $associations, $result->id);
        }
        return $results;
    }


}

