<?php


namespace App\Models;


use App\General\Application;
use PDOStatement;

abstract class Model
{
    private array $data;

    abstract public static function tableName(): string;

    public function primaryKey(): string
    {
        return 'id';
    }

    public function fillable(): array
    {
        return [];
    }

    public function registerColumn(string $attribute, $value)
    {
        $this->data[$attribute] = $value;
    }
    public function registerColumns(array $values)
    {
        $this->data = $values;
    }

    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->fillable();
        $params = array_map(fn($attribute) => ":$attribute", $attributes);

        $implodedAttributes = implode(',', $attributes);
        $implodedParams = implode(',', $params);

        $statement = self::prepare("INSERT INTO $tableName ($implodedAttributes) VALUES ($implodedParams)");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->data[$attribute]);
        }

        $statement->execute();

        return true;
    }

    public function getAttributeValue(string $attribute)
    {
        return $this->{$attribute};
    }

    public static function findWhere(array $where): mixed
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $conditions = implode("AND ",
            array_map(fn($attr) => "$attr = :$attr", $attributes)
        );
        $statement = self::prepare("SELECT * FROM $tableName WHERE $conditions");

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetchObject(static::class);;
    }

    private static function prepare(string $sql): PDOStatement
    {
        return Application::$app->getDatabase()->getConnection()->prepare($sql);
    }
}