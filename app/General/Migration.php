<?php


namespace App\General;

use JetBrains\PhpStorm\Pure;
use PDO;
use PDOException;

class Migration
{
    /**
     * @var Database
     */
    private Database $database;
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @var array
     */
    private array $logs;

    /**
     * Migration constructor.
     * @param Database $database
     */
    #[Pure] public function __construct(Database $database)
    {
        $this->database = $database;
        $this->connection = $database->getConnection();
    }

    /**
     * Apply all available migrations.
     *
     * @return void
     */
    public function applyMigrations(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$rootDirectory . '/database/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            $className = pathinfo($migration, PATHINFO_FILENAME);

            require Application::$rootDirectory . '/database/migrations/' . $className . '.php';

            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (empty($newMigrations) === false) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    /**
     * Create a migration table.
     *
     * @return void
     */
    private function createMigrationsTable(): void
    {
        try {
            $this->connection->exec(
                "CREATE TABLE IF NOT EXISTS migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=INNODB;"
            );
        } catch (PDOException $exception) {
            $this->database->setException($exception);
        }
    }

    /**
     * Get all applied migrations.
     *
     * @return array
     */
    private function getAppliedMigrations(): array
    {
        try {
            $statement = $this->connection->prepare("SELECT migration FROM migrations");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $exception) {
            $this->database->setException($exception);
        }
    }

    /**
     * Save the new migrations.
     *
     * @param array $migrations
     */
    private function saveMigrations(array $migrations): void
    {
        $str = implode(",", array_map(fn($migration) => "('$migration')", $migrations));

        try {
            $statement = $this->connection->prepare("INSERT INTO migrations (migration) VALUES $str");
            $statement->execute();
        } catch (PDOException $exception) {
            $this->database->setException($exception);
        }
    }

    /**
     * Log a message in the console.
     *
     * @param string $message
     */
    private function log(string $message)
    {
        $this->logs[] = '[' . date('d-m-Y H:i:s') . '] - ' . $message . PHP_EOL;
    }

    /**
     * Get all logs.
     *
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}