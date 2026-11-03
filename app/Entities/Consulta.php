<?php

namespace App\Entities;

use DateTimeImmutable;

class Consulta
{
    public const STATUS_AGENDADA = 'agendada';
    public const STATUS_REALIZADA = 'realizada';
    public const STATUS_CANCELADA = 'cancelada';

    /** Mesmos valores do ENUM da coluna consultas.status */
    public const STATUS = [self::STATUS_AGENDADA, self::STATUS_REALIZADA, self::STATUS_CANCELADA];

    public function __construct(
        public int $animalId,
        public int $veterinarioId,
        public DateTimeImmutable $dataHora,
        public string $status = self::STATUS_AGENDADA,
        public ?string $observacoes = null,
        public ?int $id = null,
        // Preenchidos apenas nas listagens (JOIN com animais e usuarios)
        public ?string $animalNome = null,
        public ?string $veterinarioNome = null,
    ) {
    }

    /**
     * Cria a consulta a partir de uma linha do banco (coluna data_hora) ou
     * dos dados do formulário (campos data e hora separados).
     */
    public static function fromArray(array $dados): self
    {
        $dataHora = $dados['data_hora'] ?? trim(($dados['data'] ?? '') . ' ' . ($dados['hora'] ?? ''));

        return new self(
            animalId: (int) ($dados['animal_id'] ?? 0),
            veterinarioId: (int) ($dados['veterinario_id'] ?? 0),
            dataHora: new DateTimeImmutable($dataHora),
            status: $dados['status'] ?? self::STATUS_AGENDADA,
            observacoes: trim((string) ($dados['observacoes'] ?? '')) ?: null,
            id: isset($dados['id']) ? (int) $dados['id'] : null,
            animalNome: $dados['animal_nome'] ?? null,
            veterinarioNome: $dados['veterinario_nome'] ?? null,
        );
    }

    /** Dados no formato das colunas da tabela consultas. */
    public function toArray(): array
    {
        return [
            'animal_id' => $this->animalId,
            'veterinario_id' => $this->veterinarioId,
            'data_hora' => $this->dataHora->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'observacoes' => $this->observacoes,
        ];
    }

    public function estaCancelada(): bool
    {
        return $this->status === self::STATUS_CANCELADA;
    }
}
