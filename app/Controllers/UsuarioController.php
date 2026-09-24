<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    private Usuario $usuarios;

    public function __construct()
    {
        $this->usuarios = new Usuario();
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $this->render('usuarios/index', ['usuarios' => $this->usuarios->all()]);
    }

    public function create(): void
    {
        //die('CREATE FOI CHAMADO');
        Auth::requireRole('admin');
        $temErro = !empty($_SESSION['flash']);
        $this->render('usuarios/form', [
            'usuario' => null,
            'old' => $temErro ? ($_SESSION['old'] ?? []) : [],
        ]);
        unset($_SESSION['old']);
    }

    public function store(): void
    {
        Auth::requireRole('admin');

        $dados = $this->validar($_POST, exigirSenha: true);

        if ($dados === null) {
            $this->redirect('/usuarios/novo');
        }

        $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $this->usuarios->create($dados['nome'], $dados['email'], $hash, $dados['perfil']);

        $this->flash('sucesso', 'Usuário cadastrado com sucesso.');
        $this->redirect('/usuarios');
    }

    public function edit(): void
    {
        Auth::requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $usuario = $this->usuarios->find($id);

        if (!$usuario) {
            $this->flash('erro', 'Usuário não encontrado.');
            $this->redirect('/usuarios');
        }

        $temErro = !empty($_SESSION['flash']);
        $this->render('usuarios/form', [
            'usuario' => $usuario,
            'old' => $temErro ? ($_SESSION['old'] ?? []) : [],
        ]);
        unset($_SESSION['old']);
    }
    public function update(): void
    {
        Auth::requireRole('admin');
        $id = (int) ($_POST['id'] ?? 0);

        $dados = $this->validar($_POST, exigirSenha: false, idAtual: $id);

        if ($dados === null) {
            $this->redirect("/usuarios/editar?id={$id}");
        }

        $this->usuarios->update($id, $dados['nome'], $dados['email'], $dados['perfil']);

        $this->flash('sucesso', 'Usuário atualizado com sucesso.');
        $this->redirect('/usuarios');
    }

    public function inativar(): void
    {
        Auth::requireRole('admin');
        $id = (int) ($_POST['id'] ?? 0);
        $usuario = $this->usuarios->find($id);

        if ($usuario) {
            $this->usuarios->setAtivo($id, !$usuario['ativo']);
        }

        $this->redirect('/usuarios');
    }

    private function validar(array $dados, bool $exigirSenha, ?int $idAtual = null): ?array
    {
        $nome = trim($dados['nome'] ?? '');
        $email = trim($dados['email'] ?? '');
        $perfil = $dados['perfil'] ?? '';
        $senha = $dados['senha'] ?? '';
        $confirmacao = $dados['senha_confirmacao'] ?? '';

        $erros = [];

        if ($nome === '') {
            $erros[] = 'Nome é obrigatório.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        } elseif ($this->usuarios->emailExists($email, $idAtual)) {
            $erros[] = 'Já existe um usuário com esse e-mail.';
        }

        if (!in_array($perfil, ['admin', 'atendente', 'veterinario'], true)) {
            $erros[] = 'Perfil inválido.';
        }

        if ($exigirSenha) {
            if (strlen($senha) < 6) {
                $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
            } elseif ($senha !== $confirmacao) {
                $erros[] = 'As senhas não coincidem.';
            }
        }

        if ($erros) {
            $this->flash('erro', implode(' ', $erros));
            $_SESSION['old'] = $dados;
            return null;
        }

        return compact('nome', 'email', 'perfil', 'senha');
    }
}