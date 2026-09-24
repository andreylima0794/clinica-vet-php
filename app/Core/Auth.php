<?php

namespace App\Core;

class Auth
{
    public static function login(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'perfil' => $_SESSION['usuario_perfil'],
        ];
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole(string $perfil): void
    {
        self::requireLogin();

        if ($_SESSION['usuario_perfil'] !== $perfil) {
            http_response_code(403);
            require __DIR__ . '/../Views/layout/403.php';
            exit;
        }
    }
}