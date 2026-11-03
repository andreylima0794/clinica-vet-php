<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Entities\Consulta;
use App\Repositories\AnimalRepository;
use App\Repositories\ConsultaRepository;
use App\Validators\ConsultaValidator;

class ConsultaController extends Controller
{
    private ConsultaRepository $consultas;
    private AnimalRepository $animais;

    public function __construct()
    {
        $this->consultas = new ConsultaRepository();
        $this->animais = new AnimalRepository();
    }

    public function index(): void
    {
        Auth::requireLogin();

        $filtros = [
            'status' => $_GET['status'] ?? '',
            'veterinario_id' => $_GET['veterinario_id'] ?? '',
            'data_inicio' => $_GET['data_inicio'] ?? '',
            'data_fim' => $_GET['data_fim'] ?? '',
        ];

        $this->render('consultas/index', [
            'consultas' => $this->consultas->all($filtros),
            'veterinarios' => $this->consultas->veterinarios(),
            'filtros' => $filtros,
        ]);
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

    private function formulario(?Consulta $consulta): void
    {
        $this->render('consultas/form', [
            'consulta' => $consulta,
            'animais' => $this->animais->all(),
            'veterinarios' => $this->consultas->veterinarios(),
            'old' => $_SESSION['old'] ?? [],
            'erros' => $_SESSION['erros'] ?? [],
        ]);
        unset($_SESSION['old'], $_SESSION['erros']);
    }

    private function salvar(?int $id): void
    {
        $dados = Validator::limpar($_POST);
        $erros = (new ConsultaValidator($this->consultas, $this->animais))->validar($dados, $id);

        if ($erros) {
            $_SESSION['erros'] = $erros;
            $_SESSION['old'] = $dados;
            $this->redirect($id ? "/consultas/editar/{$id}" : '/consultas/novo');
        }

        $consulta = Consulta::fromArray($dados);
        $consulta->id = $id;

        if ($id) {
            $this->consultas->update($consulta);
            $this->flash('sucesso', 'Consulta atualizada com sucesso.');
        } else {
            $this->consultas->create($consulta);
            $this->flash('sucesso', 'Consulta agendada com sucesso.');
        }

        $this->redirect('/consultas');
    }

    /** Busca a consulta pelo id ou volta para a listagem com mensagem de erro. */
    private function buscar(int $id): Consulta
    {
        $consulta = $this->consultas->find($id);

        if (!$consulta) {
            $this->flash('erro', 'Consulta não encontrada.');
            $this->redirect('/consultas');
        }

        return $consulta;
    }
}
