<?php


namespace App\Models;


use App\General\Application;
use PDOStatement;

abstract class Model
{
    private array $data;

    abstract public function tableName(): string;

    abstract public function attributes(): array;

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
        $attributes = $this->attributes();
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

    private static function prepare(string $sql): PDOStatement
    {
        return Application::$app->getDatabase()->getConnection()->prepare($sql);
    }
}