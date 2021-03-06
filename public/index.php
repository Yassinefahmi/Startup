<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\General\Application;
use App\Controllers\ContactController;

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');

$app->router->get('/contact', [ContactController::class, 'index']);
$app->router->post('/contact', [ContactController::class, 'store']);

$app->run();