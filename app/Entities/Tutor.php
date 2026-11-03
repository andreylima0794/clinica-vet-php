<?php

namespace App\Entities;

class Tutor
{
    public function __construct(
        public string $nome,
        public string $cpf,
        public ?string $telefone,
        public string $cep,
        public string $logradouro,
        public string $numero,
        public ?string $complemento,
        public string $bairro,
        public string $cidade,
        public string $estado,
        public ?int $id = null,
    ) {
    }

    /**
     * Cria o tutor a partir de uma linha do banco ou dos dados do formulário.
     * Normaliza os valores: CPF só com números, UF em maiúsculo e campos
     * opcionais vazios como null.
     */
    public static function fromArray(array $dados): self
    {
        $texto = fn(string $campo) => trim((string) ($dados[$campo] ?? ''));

        return new self(
            nome: $texto('nome'),
            cpf: preg_replace('/\D/', '', $texto('cpf')),
            telefone: $texto('telefone') ?: null,
            cep: $texto('cep'),
            // Tutores cadastrados antes da divisão do endereço só têm a coluna "endereco"
            logradouro: $texto('logradouro') ?: $texto('endereco'),
            numero: $texto('numero'),
            complemento: $texto('complemento') ?: null,
            bairro: $texto('bairro'),
            cidade: $texto('cidade'),
            estado: strtoupper($texto('estado')),
            id: isset($dados['id']) ? (int) $dados['id'] : null,
        );
    }

    /** Dados no formato das colunas da tabela tutores. */
    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'telefone' => $this->telefone,
            'endereco' => $this->enderecoCompleto(),
            'cep' => $this->cep,
            'logradouro' => $this->logradouro,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
        ];
    }

    /** Ex: "Rua A, 10, Apto 2, Centro, Curitiba - PR, 80000-000" */
    public function enderecoCompleto(): string
    {
        return implode(', ', array_filter([
            $this->logradouro . ($this->numero !== '' ? ', ' . $this->numero : ''),
            $this->complemento,
            $this->bairro,
            $this->cidade . ($this->estado !== '' ? ' - ' . $this->estado : ''),
            $this->cep,
        ]));
    }

    public function cpfFormatado(): string
    {
        if (strlen($this->cpf) !== 11) {
            return $this->cpf;
        }
        return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $this->cpf);
    }
}
