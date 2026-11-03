<?php

namespace App\Validators;

use App\Core\Validator;
use App\Repositories\TutorRepository;

class TutorValidator
{
    public function __construct(private TutorRepository $tutores)
    {
    }

    /**
     * Valida os dados do formulário de tutor.
     * Retorna os erros indexados pelo nome do campo (array vazio se estiver tudo certo).
     */
    public function validar(array $dados, ?int $id = null): array
    {
        $cpf = $dados['cpf'] ?? '';

        $validator = (new Validator())
            ->obrigatorio('nome', $dados['nome'] ?? '', 'Nome é obrigatório')
            ->cpf('cpf', $cpf, 'CPF inválido')
            ->telefone('telefone', $dados['telefone'] ?? '', 'Telefone inválido')
            ->cep('cep', $dados['cep'] ?? '', 'CEP inválido')
            ->obrigatorio('logradouro', $dados['logradouro'] ?? '', 'Logradouro é obrigatório')
            ->obrigatorio('numero', $dados['numero'] ?? '', 'Número é obrigatório')
            ->obrigatorio('bairro', $dados['bairro'] ?? '', 'Bairro é obrigatório')
            ->obrigatorio('cidade', $dados['cidade'] ?? '', 'Cidade é obrigatória')
            ->uf('estado', strtoupper($dados['estado'] ?? ''), 'Informe uma UF válida');

        if (!$validator->temErro('cpf') && $this->tutores->cpfExists(preg_replace('/\D/', '', $cpf), $id)) {
            $validator->adicionarErro('cpf', 'Já existe um tutor cadastrado com esse CPF');
        }

        return $validator->getErros();
    }
}
