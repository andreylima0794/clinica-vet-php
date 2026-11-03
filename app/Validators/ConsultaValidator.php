<?php

namespace App\Validators;

use App\Core\Validator;
use App\Entities\Consulta;
use App\Repositories\AnimalRepository;
use App\Repositories\ConsultaRepository;
use DateTimeImmutable;

class ConsultaValidator
{
    public function __construct(
        private ConsultaRepository $consultas,
        private AnimalRepository $animais,
    ) {
    }

    /**
     * Valida os dados do formulário de consulta.
     * Retorna os erros indexados pelo nome do campo (array vazio se estiver tudo certo).
     */
    public function validar(array $dados, ?int $id = null): array
    {
        $animalId = (int) ($dados['animal_id'] ?? 0);
        $veterinarioId = (int) ($dados['veterinario_id'] ?? 0);
        $data = $dados['data'] ?? '';
        $hora = $dados['hora'] ?? '';
        $status = $dados['status'] ?? '';

        $validator = (new Validator())
            ->obrigatorio('animal_id', $animalId ?: '', 'Selecione um animal')
            ->obrigatorio('veterinario_id', $veterinarioId ?: '', 'Selecione um veterinário')
            ->obrigatorio('data', $data, 'Data é obrigatória')
            ->obrigatorio('hora', $hora, 'Horário é obrigatório')
            ->naLista('status', $status, Consulta::STATUS, 'Status inválido');

        if (!$validator->temErro('animal_id') && $this->animais->find($animalId) === null) {
            $validator->adicionarErro('animal_id', 'Animal não encontrado');
        }

        $idsVeterinarios = array_map('intval', array_column($this->consultas->veterinarios(), 'id'));
        if (!$validator->temErro('veterinario_id') && !in_array($veterinarioId, $idsVeterinarios, true)) {
            $validator->adicionarErro('veterinario_id', 'Veterinário não encontrado ou inativo');
        }

        if ($data !== '' && $hora !== '') {
            $dataHora = Validator::criarData("$data $hora", 'Y-m-d H:i');

            if ($dataHora === null) {
                $validator->adicionarErro('data', 'Informe uma data e horário válidos');
            } elseif ($status === Consulta::STATUS_AGENDADA && $dataHora < new DateTimeImmutable()) {
                // Consultas já realizadas ou canceladas podem ter data no passado
                $validator->adicionarErro('data', 'Uma consulta agendada não pode ser marcada no passado');
            }
        }

        // O conflito de horário só é verificado se o restante dos dados estiver correto
        if (!$validator->temErro()) {
            $consulta = Consulta::fromArray($dados);
            $consulta->id = $id;

            if (!$consulta->estaCancelada() && $this->consultas->existeConflito($consulta)) {
                $validator->adicionarErro('hora', 'Este veterinário já tem consulta marcada nesse horário');
            }
        }

        return $validator->getErros();
    }
}
