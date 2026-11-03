<?php

namespace App\Entities;

use DateTimeImmutable;

class Animal
{
    public function __construct(
        public int $tutorId,
        public string $nome,
        public string $especie,
        public ?string $raca,
        public ?DateTimeImmutable $dataNascimento,
        public ?float $peso,
        public ?int $id = null,
        // Preenchido apenas nas listagens (JOIN com tutores)
        public ?string $tutorNome = null,
    ) {
    }

    /** Cria o animal a partir de uma linha do banco ou dos dados do formulário. */
    public static function fromArray(array $dados): self
    {
        $texto = fn(string $campo) => trim((string) ($dados[$campo] ?? ''));

        return new self(
            tutorId: (int) ($dados['tutor_id'] ?? 0),
            nome: $texto('nome'),
            especie: $texto('especie'),
            raca: $texto('raca') ?: null,
            dataNascimento: $texto('data_nascimento') !== '' ? new DateTimeImmutable($texto('data_nascimento')) : null,
            peso: $texto('peso') !== '' ? (float) $texto('peso') : null,
            id: isset($dados['id']) ? (int) $dados['id'] : null,
            tutorNome: $dados['tutor_nome'] ?? null,
        );
    }

    /** Dados no formato das colunas da tabela animais. */
    public function toArray(): array
    {
        return [
            'tutor_id' => $this->tutorId,
            'nome' => $this->nome,
            'especie' => $this->especie,
            'raca' => $this->raca,
            'data_nascimento' => $this->dataNascimento?->format('Y-m-d'),
            'peso' => $this->peso,
        ];
    }

    /** Ex: "3 anos", "1 ano", "5 meses". Null se a data de nascimento não foi informada. */
    public function idade(): ?string
    {
        if ($this->dataNascimento === null) {
            return null;
        }

        $diferenca = $this->dataNascimento->diff(new DateTimeImmutable('today'));

        if ($diferenca->y > 0) {
            return $diferenca->y . ($diferenca->y === 1 ? ' ano' : ' anos');
        }
        return $diferenca->m . ($diferenca->m === 1 ? ' mês' : ' meses');
    }
}
