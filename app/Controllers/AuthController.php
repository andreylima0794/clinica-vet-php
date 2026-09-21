<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/usuarios');
        }

        $this->render('auth/login', ['old' => $_SESSION['old'] ?? []]);
        unset($_SESSION['old']);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($email === '' || $senha === '') {
            $this->flash('erro', 'Preencha e-mail e senha.');
            $_SESSION['old'] = ['email' => $email];
            $this->redirect('/login');
        }

        $usuario = (new Usuario())->findByEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha']) || !$usuario['ativo']) {
            $this->flash('erro', 'E-mail ou senha inválidos.');
            $_SESSION['old'] = ['email' => $email];
            $this->redirect('/login');
        }

        Auth::login($usuario);
        $this->redirect('/usuarios');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}