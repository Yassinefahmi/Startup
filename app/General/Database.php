<?php


namespace App\General;


use Exception;
use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO
     */
    private PDO $connection;

    private array $exceptions = [];

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $environment = new Environment();
        $credentials = $environment->getDatabaseEnvironmentVariables();

        try {
            $this->connection = new PDO(
                "mysql:host={$credentials['DB_HOST']};dbname={$credentials['DB_DATABASE']};",
                $credentials['DB_USERNAME'],
                $credentials['DB_PASSWORD']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            $this->exceptions[] = $exception->getMessage();
        }
    }

    /**
     * Get the PDO object.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Get all PDO exceptions.
     *
     * @return array
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Set a PDO exception.
     *
     * @param PDOException $exception
     */
    public function setException(PDOException $exception): void
    {
        $this->exceptions[] = $exception->getMessage();
    }
}