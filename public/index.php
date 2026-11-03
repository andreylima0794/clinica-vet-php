<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Controllers\TutorController;
use App\Controllers\AnimalController;
use App\Controllers\ConsultaController;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

$logger = new Logger('clinica-vet');
$logger->pushHandler(new StreamHandler($logDir . '/app.log', Level::Error));

set_error_handler(function ($severity, $message, $file, $line) {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (\Throwable $e) use ($logger) {
    $logger->error($e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
    http_response_code(500);
    require __DIR__ . '/../app/Views/layout/500.php';
    exit;
});

$router = new Router();

$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/usuarios', [UsuarioController::class, 'index']);
$router->get('/usuarios/novo', [UsuarioController::class, 'create']);
$router->post('/usuarios/novo', [UsuarioController::class, 'store']);
$router->get('/usuarios/editar/{id}', [UsuarioController::class, 'edit']);
$router->post('/usuarios/editar/{id}', [UsuarioController::class, 'update']);
$router->post('/usuarios/inativar/{id}', [UsuarioController::class, 'inativar']);

$router->get('/tutores', [TutorController::class, 'index']);
$router->get('/tutores/novo', [TutorController::class, 'novo']);
$router->post('/tutores/novo', [TutorController::class, 'criar']);
$router->get('/tutores/editar/{id}', [TutorController::class, 'editar']);
$router->post('/tutores/editar/{id}', [TutorController::class, 'atualizar']);

$router->get('/animais', [AnimalController::class, 'index']);
$router->get('/animais/novo', [AnimalController::class, 'novo']);
$router->post('/animais/novo', [AnimalController::class, 'criar']);
$router->get('/animais/editar/{id}', [AnimalController::class, 'editar']);
$router->post('/animais/editar/{id}', [AnimalController::class, 'atualizar']);

$router->get('/consultas', [ConsultaController::class, 'index']);
$router->get('/consultas/novo', [ConsultaController::class, 'novo']);
$router->post('/consultas/novo', [ConsultaController::class, 'criar']);
$router->get('/consultas/editar/{id}', [ConsultaController::class, 'editar']);
$router->post('/consultas/editar/{id}', [ConsultaController::class, 'atualizar']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);