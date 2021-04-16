<?php


namespace App\General;


use Dotenv\Dotenv;
use Exception;
use RuntimeException;

class Environment
{
    /**
     * @var array
     */
    private array $databaseEnvironmentVariables;

    /**
     * @var array
     */
    private array $applicationVariables;

    /**
     * @var RuntimeException|Exception|null
     */
    private RuntimeException|Exception|null $exception = null;

    /**
     * Environment constructor.
     */
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        try {
            $dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'])->notEmpty();
            $dotenv->required('DB_PASSWORD');
        } catch (RuntimeException $exception) {
            $this->exception = $exception;
        }

        $this->databaseEnvironmentVariables = [
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_PORT' => $_ENV['DB_PORT'],
            'DB_DATABASE' => $_ENV['DB_DATABASE'],
            'DB_USERNAME' => $_ENV['DB_USERNAME'],
            'DB_PASSWORD' => $_ENV['DB_PASSWORD']
        ];

        $this->applicationVariables = [
            'APP_NAME' => $_ENV['APP_NAME'],
            'APP_URL' => $_ENV['APP_URL']
        ];
    }

    /**
     * Get all database environment variables.
     *
     * @return array
     */
    public function getDatabaseEnvironmentVariables(): array
    {
        return $this->databaseEnvironmentVariables;
    }

    /**
     * Get all application environment variables.
     *
     * @return array
     */
    public function getApplicationVariables(): array
    {
        return $this->applicationVariables;
    }

    /**
     * Get an exception if the variables does not meet the requirements.
     *
     * @return Exception|RuntimeException|null
     */
    public function getException(): Exception|RuntimeException|null
    {
        return $this->exception;
    }
}