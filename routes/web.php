<?php

/**
 * Configure web routes.
 *
 * @var $app Application
 */

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\General\Application;

$app->getRouter()->redirect('/', '/login');

$app->getRouter()->get('/login', [LoginController::class, 'index']);
$app->getRouter()->post('/login', [LoginController::class, 'authenticate']);

$app->getRouter()->get('/register', [RegisterController::class, 'index']);
$app->getRouter()->post('/register', [RegisterController::class, 'store']);

$app->getRouter()->get('/home', [HomeController::class, 'index']);