<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Entities\Animal;
use App\Repositories\AnimalRepository;
use App\Repositories\TutorRepository;
use App\Validators\AnimalValidator;

class AnimalController extends Controller
{
    private AnimalRepository $animais;
    private TutorRepository $tutores;

    public function __construct()
    {
        $this->animais = new AnimalRepository();
        $this->tutores = new TutorRepository();
    }

    public function index(): void
    {
        Auth::requireLogin();
        $this->render('animais/index', ['animais' => $this->animais->all()]);
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

    private function formulario(?Animal $animal): void
    {
        $this->render('animais/form', [
            'animal' => $animal,
            'tutores' => $this->tutores->all(),
            'old' => $_SESSION['old'] ?? [],
            'erros' => $_SESSION['erros'] ?? [],
        ]);
        unset($_SESSION['old'], $_SESSION['erros']);
    }

    private function salvar(?int $id): void
    {
        $dados = Validator::limpar($_POST);
        $erros = (new AnimalValidator($this->tutores))->validar($dados);

        if ($erros) {
            $_SESSION['erros'] = $erros;
            $_SESSION['old'] = $dados;
            $this->redirect($id ? "/animais/editar/{$id}" : '/animais/novo');
        }

        $animal = Animal::fromArray($dados);
        $animal->id = $id;

        if ($id) {
            $this->animais->update($animal);
            $this->flash('sucesso', 'Animal atualizado com sucesso.');
        } else {
            $this->animais->create($animal);
            $this->flash('sucesso', 'Animal cadastrado com sucesso.');
        }

        $this->redirect('/animais');
    }

    /** Busca o animal pelo id ou volta para a listagem com mensagem de erro. */
    private function buscar(int $id): Animal
    {
        $animal = $this->animais->find($id);

        if (!$animal) {
            $this->flash('erro', 'Animal não encontrado.');
            $this->redirect('/animais');
        }

        return $animal;
    }
}
