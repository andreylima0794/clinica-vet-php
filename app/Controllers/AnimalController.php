<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Animal;
use App\Models\Tutor;

class AnimalController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $animais = (new Animal())->all();
        $this->render('animais/index', ['animais' => $animais]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        $this->render('animais/form', [
            'animal' => null,
            'tutores' => (new Tutor())->all(),
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
        $animal = (new Animal())->find($id);

        if (!$animal) {
            $this->flash('erro', 'Animal não encontrado.');
            $this->redirect('/animais');
        }

        $this->render('animais/form', [
            'animal' => $animal,
            'tutores' => (new Tutor())->all(),
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
        $tutorId = (int) ($_POST['tutor_id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $raca = trim($_POST['raca'] ?? '');
        $dataNascimento = $_POST['data_nascimento'] ?: null;
        $peso = $_POST['peso'] !== '' ? (float) $_POST['peso'] : null;

        $validator = new Validator();
        $validator->obrigatorio('tutor_id', $tutorId ?: '', 'Selecione um tutor')
                  ->obrigatorio('nome', $nome, 'Nome é obrigatório')
                  ->obrigatorio('especie', $especie, 'Espécie é obrigatória');

        if ($dataNascimento) {
            $dataValida = \DateTime::createFromFormat('!Y-m-d', $dataNascimento);
            $errosData = \DateTime::getLastErrors();
            $dataInvalida = $dataValida === false
                || ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0));

            if ($dataInvalida) {
                $_SESSION['erros']['data_nascimento'] = 'Informe uma data de nascimento válida';
            } elseif ($dataNascimento < '1900-01-01') {
                $_SESSION['erros']['data_nascimento'] = 'Data de nascimento deve ser a partir de 01/01/1900';
            } elseif ($dataNascimento > date('Y-m-d')) {
                $_SESSION['erros']['data_nascimento'] = 'Data de nascimento não pode ser no futuro';
            }
        }

        if ($validator->temErro() || isset($_SESSION['erros']['data_nascimento'])) {
            $_SESSION['erros'] = array_merge($validator->getErros(), $_SESSION['erros'] ?? []);
            $_SESSION['old'] = $_POST;
            $this->redirect($id ? '/animais/editar?id=' . $id : '/animais/novo');
        }

        $animalModel = new Animal();

        if ($id) {
            $animalModel->update($id, $tutorId, $nome, $especie, $raca, $dataNascimento, $peso);
            $this->flash('sucesso', 'Animal atualizado com sucesso.');
        } else {
            $animalModel->create($tutorId, $nome, $especie, $raca, $dataNascimento, $peso);
            $this->flash('sucesso', 'Animal cadastrado com sucesso.');
        }

        $this->redirect('/animais');
    }
}