<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\General\Application;

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');

$app->router->get('/page', 'page');

$app->run();