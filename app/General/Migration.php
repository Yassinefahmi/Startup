<?php


namespace App\General;

use PDO;

class Migration
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function applyMigrations()
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

    private function createMigrationsTable()
    {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;"
        );
    }

    private function getAppliedMigrations(): array
    {
        $statement = $this->connection->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($migration) => "('$migration')", $migrations));
        $statement = $this->connection->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    protected function log(string $message)
    {
        echo '[' . date('d-m-Y H:i:s') . '] - ' . $message . PHP_EOL;
    }
}