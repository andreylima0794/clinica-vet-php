<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Validator;
use App\Models\Animal;
use App\Models\Consulta;

class ConsultaController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $filtros = [
            'status' => $_GET['status'] ?? '',
            'veterinario_id' => $_GET['veterinario_id'] ?? '',
            'data_inicio' => $_GET['data_inicio'] ?? '',
            'data_fim' => $_GET['data_fim'] ?? '',
        ];

        $consultas = (new Consulta())->all($filtros);

        $this->render('consultas/index', [
            'consultas' => $consultas,
            'veterinarios' => $this->veterinarios(),
            'filtros' => $filtros,
        ]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        $this->render('consultas/form', [
            'consulta' => null,
            'animais' => (new Animal())->all(),
            'veterinarios' => $this->veterinarios(),
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
        $consulta = (new Consulta())->find($id);

        if (!$consulta) {
            $this->flash('erro', 'Consulta não encontrada.');
            $this->redirect('/consultas');
        }

        $this->render('consultas/form', [
            'consulta' => $consulta,
            'animais' => (new Animal())->all(),
            'veterinarios' => $this->veterinarios(),
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
        $animalId = (int) ($_POST['animal_id'] ?? 0);
        $veterinarioId = (int) ($_POST['veterinario_id'] ?? 0);
        $data = $_POST['data'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $dataHora = trim("$data $hora");
        $status = $_POST['status'] ?? 'agendada';
        $observacoes = trim($_POST['observacoes'] ?? '') ?: null;

        $validator = new Validator();
        $validator->obrigatorio('animal_id', $animalId ?: '', 'Selecione um animal')
                  ->obrigatorio('veterinario_id', $veterinarioId ?: '', 'Selecione um veterinário')
                  ->obrigatorio('data', $data, 'Data é obrigatória')
                  ->obrigatorio('hora', $hora, 'Horário é obrigatório');

        $consultaModel = new Consulta();

        if (!$validator->temErro() && $consultaModel->existeConflito($veterinarioId, $dataHora, $id)) {
            $_SESSION['erros']['hora'] = 'Este veterinário já tem consulta marcada nesse horário';
        }

        if ($validator->temErro() || isset($_SESSION['erros']['hora'])) {
            $_SESSION['erros'] = array_merge($validator->getErros(), $_SESSION['erros'] ?? []);
            $_SESSION['old'] = $_POST;
            $this->redirect($id ? '/consultas/editar?id=' . $id : '/consultas/novo');
        }

        if ($id) {
            $consultaModel->update($id, $animalId, $veterinarioId, $dataHora, $status, $observacoes);
            $this->flash('sucesso', 'Consulta atualizada com sucesso.');
        } else {
            $consultaModel->create($animalId, $veterinarioId, $dataHora, $status, $observacoes);
            $this->flash('sucesso', 'Consulta agendada com sucesso.');
        }

        $this->redirect('/consultas');
    }

    private function veterinarios(): array
    {
        $stmt = Database::getConnection()->query(
            "SELECT id, nome FROM usuarios WHERE perfil = 'veterinario' AND ativo = 1 ORDER BY nome"
        );
        return $stmt->fetchAll();
    }
}