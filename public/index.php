<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UsuarioController;

set_error_handler(function ($severity, $message, $file, $line) {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    require __DIR__ . '/../app/Views/layout/500.php';
    exit;
});

$router = new Router();

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/usuarios', [UsuarioController::class, 'index']);
$router->get('/usuarios/novo', [UsuarioController::class, 'create']);
$router->post('/usuarios/novo', [UsuarioController::class, 'store']);
$router->get('/usuarios/editar', [UsuarioController::class, 'edit']);
$router->post('/usuarios/editar', [UsuarioController::class, 'update']);
$router->post('/usuarios/inativar', [UsuarioController::class, 'inativar']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);