<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Animal
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT animais.*, tutores.nome AS tutor_nome
             FROM animais
             JOIN tutores ON tutores.id = animais.tutor_id
             ORDER BY animais.nome'
        );
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM animais WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $tutorId, string $nome, string $especie, string $raca, ?string $dataNascimento, ?float $peso): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO animais (tutor_id, nome, especie, raca, data_nascimento, peso) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$tutorId, $nome, $especie, $raca, $dataNascimento, $peso]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $tutorId, string $nome, string $especie, string $raca, ?string $dataNascimento, ?float $peso): void
    {
        $stmt = $this->db->prepare(
            'UPDATE animais SET tutor_id = ?, nome = ?, especie = ?, raca = ?, data_nascimento = ?, peso = ? WHERE id = ?'
        );
        $stmt->execute([$tutorId, $nome, $especie, $raca, $dataNascimento, $peso, $id]);
    }
}