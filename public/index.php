<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\General\Application;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

$config = [
    'userModel' => \App\Models\User::class
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/login', [LoginController::class, 'index']);
$app->router->post('/login', [LoginController::class, 'authenticate']);

$app->router->get('/register', [RegisterController::class, 'index']);
$app->router->post('/register', [RegisterController::class, 'store']);

$app->run();