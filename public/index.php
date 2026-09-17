<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;

$router = new Router();

// Rotas serão registradas aqui pelos controllers
// Exemplo: $router->get('/login', [App\Controllers\AuthController::class, 'showLogin']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
