<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Entities\Consulta;

class ConsultaRepository extends Repository
{
    protected string $tabela = 'consultas';

    protected function paraEntidade(array $linha): Consulta
    {
        return Consulta::fromArray($linha);
    }

    /** @return Consulta[] */
    public function all(array $filtros = []): array
    {
        $sql = "SELECT consultas.*, animais.nome AS animal_nome, usuarios.nome AS veterinario_nome
                FROM consultas
                JOIN animais ON animais.id = consultas.animal_id
                JOIN usuarios ON usuarios.id = consultas.veterinario_id
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['status'])) {
            $sql .= " AND consultas.status = ?";
            $params[] = $filtros['status'];
        }
        if (!empty($filtros['veterinario_id'])) {
            $sql .= " AND consultas.veterinario_id = ?";
            $params[] = $filtros['veterinario_id'];
        }
        if (!empty($filtros['data_inicio'])) {
            $sql .= " AND consultas.data_hora >= ?";
            $params[] = $filtros['data_inicio'] . ' 00:00:00';
        }
        if (!empty($filtros['data_fim'])) {
            $sql .= " AND consultas.data_hora <= ?";
            $params[] = $filtros['data_fim'] . ' 23:59:59';
        }

        $sql .= " ORDER BY consultas.data_hora DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $this->paraEntidades($stmt->fetchAll());
    }

    /** Verifica se o veterinário já tem outra consulta (não cancelada) no mesmo horário. */
    public function existeConflito(Consulta $consulta): bool
    {
        $sql = "SELECT id FROM consultas WHERE veterinario_id = ? AND data_hora = ? AND status != 'cancelada'";
        $params = [$consulta->veterinarioId, $consulta->dataHora->format('Y-m-d H:i:s')];

        if ($consulta->id !== null) {
            $sql .= ' AND id != ?';
            $params[] = $consulta->id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    /**
     * Veterinários ativos que podem receber consultas (lista para os selects).
     * Retorna apenas id e nome, por isso não usa entidade.
     */
    public function veterinarios(): array
    {
        $stmt = $this->db->query(
            "SELECT id, nome FROM usuarios WHERE perfil = 'veterinario' AND ativo = 1 ORDER BY nome"
        );
        return $stmt->fetchAll();
    }

    public function create(Consulta $consulta): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO consultas (animal_id, veterinario_id, data_hora, status, observacoes)
             VALUES (:animal_id, :veterinario_id, :data_hora, :status, :observacoes)'
        );
        $stmt->execute($consulta->toArray());
        return (int) $this->db->lastInsertId();
    }

    public function update(Consulta $consulta): void
    {
        $stmt = $this->db->prepare(
            'UPDATE consultas SET animal_id = :animal_id, veterinario_id = :veterinario_id, data_hora = :data_hora,
             status = :status, observacoes = :observacoes
             WHERE id = :id'
        );
        $stmt->execute($consulta->toArray() + ['id' => $consulta->id]);
    }
}
