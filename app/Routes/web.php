<?php

use Core\Router;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;

Router::get('/', [HomeController::class, 'index'], ['name' => 'home']);

// Guest routes — use 'guest' mode explicitly
Router::group(['middleware' => [[AuthMiddleware::class, 'guest']]], function () {
    Router::get('/login', [AuthController::class, 'login'], ['name' => 'login']);
    Router::post('/login', [AuthController::class, 'loginPost']);
    Router::get('/register', [AuthController::class, 'register'], ['name' => 'register']);
    Router::post('/register', [AuthController::class, 'registerPost']);
});

// Authenticated routes — use 'auth' mode (default)
Router::group(['middleware' => [[AuthMiddleware::class, 'auth']]], function () {
    Router::get('/logout', [AuthController::class, 'logout']);
    Router::get('/dashboard', [DashboardController::class, 'index'], ['name' => 'dashboard']);
});
