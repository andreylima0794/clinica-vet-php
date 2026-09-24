<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Consulta
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

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
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM consultas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function existeConflito(int $veterinarioId, string $dataHora, ?int $exceptId = null): bool
    {
        $sql = "SELECT id FROM consultas WHERE veterinario_id = ? AND data_hora = ? AND status != 'cancelada'";
        $params = [$veterinarioId, $dataHora];

        if ($exceptId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(int $animalId, int $veterinarioId, string $dataHora, string $status, ?string $observacoes): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO consultas (animal_id, veterinario_id, data_hora, status, observacoes) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$animalId, $veterinarioId, $dataHora, $status, $observacoes]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $animalId, int $veterinarioId, string $dataHora, string $status, ?string $observacoes): void
    {
        $stmt = $this->db->prepare(
            'UPDATE consultas SET animal_id = ?, veterinario_id = ?, data_hora = ?, status = ?, observacoes = ? WHERE id = ?'
        );
        $stmt->execute([$animalId, $veterinarioId, $dataHora, $status, $observacoes, $id]);
    }
}