<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\General\Application;

$app = new Application((__DIR__));

$app->getMigration()->applyMigrations();

foreach ($app->getMigration()->getLogs() as $log) {
    echo $log;
}