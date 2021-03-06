<?php


namespace App\General;


use Dotenv\Dotenv;

class Environment
{
    public array $environmentVariables;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();
        $dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'])->notEmpty();
        $dotenv->required('DB_PASSWORD');

        $this->environmentVariables = $_ENV;
    }

    public function getDatabaseEnvironmentVariables(): array
    {
        return [
            'DB_HOST' => $this->environmentVariables['DB_HOST'],
            'DB_PORT' => $this->environmentVariables['DB_PORT'],
            'DB_DATABASE' => $this->environmentVariables['DB_DATABASE'],
            'DB_USERNAME' => $this->environmentVariables['DB_USERNAME'],
            'DB_PASSWORD' => $this->environmentVariables['DB_PASSWORD']
        ];
    }
}