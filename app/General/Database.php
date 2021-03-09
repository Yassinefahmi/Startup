<?php


namespace App\General;


use PDO;
use PDOException;

class Database
{
    private PDO $connection;
    private PDOException $exception;

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
            $this->exception = $exception;
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getException(): PDOException
    {
        return $this->exception;
    }
}