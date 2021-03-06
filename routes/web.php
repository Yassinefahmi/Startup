<?php

/**
 * @var $app Application
 */

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\General\Application;

$app->getRouter()->redirect('/', '/login');

/**
 * Authentication
 */
$app->getRouter()->get('/login', [LoginController::class, 'index'], 'login.index');
$app->getRouter()->post('/login', [LoginController::class, 'authenticate'], 'login');
$app->getRouter()->get('/logout', [LoginController::class, 'logout'], 'logout');
$app->getRouter()->post('/register', [RegisterController::class, 'store'], 'register');

$app->getRouter()->get('/home', [HomeController::class, 'index'], 'home');