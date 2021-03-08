<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\General\Application;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

$app = new Application(dirname(__DIR__));

$app->router->get('/test', 'string');
$app->router->get('/login', [LoginController::class, 'index']);
$app->router->post('/login', [LoginController::class, 'authenticate']);

$app->router->get('/register', [RegisterController::class, 'index']);
$app->router->post('/register', [RegisterController::class, 'store']);

$app->run();