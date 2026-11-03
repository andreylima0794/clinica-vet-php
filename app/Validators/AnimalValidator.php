<?php

namespace App\Validators;

use App\Core\Validator;
use App\Repositories\TutorRepository;

class AnimalValidator
{
    public function __construct(private TutorRepository $tutores)
    {
    }

    /**
     * Valida os dados do formulário de animal.
     * Retorna os erros indexados pelo nome do campo (array vazio se estiver tudo certo).
     */
    public function validar(array $dados): array
    {
        $tutorId = (int) ($dados['tutor_id'] ?? 0);
        $dataNascimento = $dados['data_nascimento'] ?? '';

        $validator = (new Validator())
            ->obrigatorio('tutor_id', $tutorId ?: '', 'Selecione um tutor')
            ->obrigatorio('nome', $dados['nome'] ?? '', 'Nome é obrigatório')
            ->obrigatorio('especie', $dados['especie'] ?? '', 'Espécie é obrigatória')
            ->data('data_nascimento', $dataNascimento, 'Informe uma data de nascimento válida')
            ->numeroEntre('peso', $dados['peso'] ?? '', 0.01, 999.99, 'Peso deve estar entre 0,01 e 999,99 kg');

        if (!$validator->temErro('tutor_id') && $this->tutores->find($tutorId) === null) {
            $validator->adicionarErro('tutor_id', 'Tutor não encontrado');
        }

        // A data só é comparada se estiver em um formato válido (Y-m-d permite comparar como texto)
        if ($dataNascimento !== '' && !$validator->temErro('data_nascimento')) {
            if ($dataNascimento < '1900-01-01') {
                $validator->adicionarErro('data_nascimento', 'Data de nascimento deve ser a partir de 01/01/1900');
            } elseif ($dataNascimento > date('Y-m-d')) {
                $validator->adicionarErro('data_nascimento', 'Data de nascimento não pode ser no futuro');
            }
        }

        return $validator->getErros();
    }
}
