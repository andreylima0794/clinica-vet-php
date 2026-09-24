<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Tutor;

class TutorController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $tutores = (new Tutor())->all();
        $this->render('tutores/index', ['tutores' => $tutores]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        $this->render('tutores/form', [
            'tutor' => null,
            'old' => $_SESSION['old'] ?? [],
            'erros' => $_SESSION['erros'] ?? [],
        ]);
        unset($_SESSION['old'], $_SESSION['erros']);
    }

    public function criar(): void
    {
        Auth::requireLogin();
        $this->salvar(null);
    }

    public function editar(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $tutor = (new Tutor())->find($id);

        if (!$tutor) {
            $this->flash('erro', 'Tutor não encontrado.');
            $this->redirect('/tutores');
        }

        $this->render('tutores/form', [
            'tutor' => $tutor,
            'old' => $_SESSION['old'] ?? [],
            'erros' => $_SESSION['erros'] ?? [],
        ]);
        unset($_SESSION['old'], $_SESSION['erros']);
    }

    public function atualizar(): void
    {
        Auth::requireLogin();
        $id = (int) ($_POST['id'] ?? 0);
        $this->salvar($id);
    }

    private function salvar(?int $id): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');

        $validator = new Validator();
        $validator->obrigatorio('nome', $nome, 'Nome é obrigatório')
                  ->cpf('cpf', $cpf, 'CPF inválido');

        if ($validator->temErro()) {
            $_SESSION['erros'] = $validator->getErros();
            $_SESSION['old'] = $_POST;
            $this->redirect($id ? '/tutores/editar?id=' . $id : '/tutores/novo');
        }

        $tutorModel = new Tutor();

        if ($id) {
            $tutorModel->update($id, $nome, $cpf, $telefone, $endereco);
            $this->flash('sucesso', 'Tutor atualizado com sucesso.');
        } else {
            $tutorModel->create($nome, $cpf, $telefone, $endereco);
            $this->flash('sucesso', 'Tutor cadastrado com sucesso.');
        }

        $this->redirect('/tutores');
    }
}