<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\General\Application;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Models\User;

$config = [
    'userModel' => User::class
];

$app = new Application(dirname(__DIR__), $config);

/**
 * Configure routes
 */

$app->getRouter()->get('/login', [LoginController::class, 'index']);
$app->getRouter()->post('/login', [LoginController::class, 'authenticate']);

$app->getRouter()->get('/register', [RegisterController::class, 'index']);
$app->getRouter()->post('/register', [RegisterController::class, 'store']);

$app->getRouter()->get('/home', [\App\Controllers\HomeController::class, 'index']);

echo $app->getRouter()->resolve();