<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Entities\Tutor;
use App\Repositories\TutorRepository;
use App\Validators\TutorValidator;

class TutorController extends Controller
{
    private TutorRepository $tutores;

    public function __construct()
    {
        $this->tutores = new TutorRepository();
    }

    public function index(): void
    {
        Auth::requireLogin();
        $this->render('tutores/index', ['tutores' => $this->tutores->all()]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        $this->formulario(null);
    }

    public function criar(): void
    {
        Auth::requireLogin();
        $this->salvar(null);
    }

    public function editar(string $id): void
    {
        Auth::requireLogin();
        $this->formulario($this->buscar((int) $id));
    }

    public function atualizar(string $id): void
    {
        Auth::requireLogin();
        $this->salvar($this->buscar((int) $id)->id);
    }

    private function formulario(?Tutor $tutor): void
    {
        $this->render('tutores/form', [
            'tutor' => $tutor,
            'old' => $_SESSION['old'] ?? [],
            'erros' => $_SESSION['erros'] ?? [],
        ]);
        unset($_SESSION['old'], $_SESSION['erros']);
    }

    private function salvar(?int $id): void
    {
        $dados = Validator::limpar($_POST);
        $erros = (new TutorValidator($this->tutores))->validar($dados, $id);

        if ($erros) {
            $_SESSION['erros'] = $erros;
            $_SESSION['old'] = $dados;
            $this->redirect($id ? "/tutores/editar/{$id}" : '/tutores/novo');
        }

        $tutor = Tutor::fromArray($dados);
        $tutor->id = $id;

        if ($id) {
            $this->tutores->update($tutor);
            $this->flash('sucesso', 'Tutor atualizado com sucesso.');
        } else {
            $this->tutores->create($tutor);
            $this->flash('sucesso', 'Tutor cadastrado com sucesso.');
        }

        $this->redirect('/tutores');
    }

    /** Busca o tutor pelo id ou volta para a listagem com mensagem de erro. */
    private function buscar(int $id): Tutor
    {
        $tutor = $this->tutores->find($id);

        if (!$tutor) {
            $this->flash('erro', 'Tutor não encontrado.');
            $this->redirect('/tutores');
        }

        return $tutor;
    }
}
