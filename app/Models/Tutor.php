<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Tutor
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM tutores ORDER BY nome');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tutores WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nome, string $cpf, string $telefone, string $endereco): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tutores (nome, cpf, telefone, endereco) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nome, $cpf, $telefone, $endereco]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $nome, string $cpf, string $telefone, string $endereco): void
    {
        $stmt = $this->db->prepare(
            'UPDATE tutores SET nome = ?, cpf = ?, telefone = ?, endereco = ? WHERE id = ?'
        );
        $stmt->execute([$nome, $cpf, $telefone, $endereco, $id]);
    }
}