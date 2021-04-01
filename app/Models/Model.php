<?php


namespace App\Models;


use App\General\Application;
use PDO;
use PDOException;
use PDOStatement;

abstract class Model
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Assign a table name.
     *
     * @return string
     */
    abstract public static function tableName(): string;

    /**
     * Get the primary key.
     *
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * Get columns that can be filled.
     *
     * @return array
     */
    public function fillAble(): array
    {
        return [];
    }

    /**
     * Set a value for given column.
     *
     * @param string $attribute
     * @param $value
     */
    public function registerColumn(string $attribute, $value)
    {
        $this->data[$attribute] = $value;
    }

    /**
     * Set values for columns.
     *
     * @param array $values
     */
    public function registerColumns(array $values)
    {
        $this->data = $values;
    }

    /**
     * Save a model.
     *
     * @return void
     */
    public function save(): void
    {
        $tableName = $this->tableName();
        $attributes = $this->fillable();
        $params = array_map(fn($attribute) => ":$attribute", $attributes);

        $implodedAttributes = implode(',', $attributes);
        $implodedParams = implode(',', $params);

        try {
            $statement = self::prepare("INSERT INTO $tableName ($implodedAttributes) VALUES ($implodedParams)");

            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $this->data[$attribute]);
            }

            $statement->execute();
        } catch (PDOException $exception) {
            Application::$app->getDatabase()->setException($exception);
        }
    }

    /**
     * Get value of given attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getAttributeValue(string $attribute): mixed
    {
        return $this->{$attribute};
    }

    /**
     * Get PDO statement if there is a where condition.
     *
     * @param array $where
     * @return PDOStatement
     */
    private static function where(array $where): PDOStatement
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

        return $statement;
    }

    /**
     * Get a model that meets the given where conditions.
     *
     * @param array $where
     * @return mixed
     */
    public static function findOneWhere(array $where): mixed
    {
        return self::where($where)->fetchObject(static::class);
    }

    /**
     * Get all models that meets the given where conditions.
     *
     * @param array $where
     * @return array
     */
    public static function findAllWhere(array $where): array
    {
        return self::where($where)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    /**
     * Prepare a sql query.
     *
     * @param string $sql
     * @return PDOStatement
     */
    private static function prepare(string $sql): PDOStatement
    {
        return Application::$app->getDatabase()->getConnection()->prepare($sql);
    }
}