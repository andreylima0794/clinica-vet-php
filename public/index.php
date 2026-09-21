<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UsuarioController;

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