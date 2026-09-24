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

    public function cpfExists(string $cpf, ?int $exceptId = null): bool
    {
        $sql = 'SELECT id FROM tutores WHERE cpf = ?';
        $params = [$cpf];

        if ($exceptId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(
        string $nome,
        string $cpf,
        string $telefone,
        string $endereco,
        string $cep,
        string $logradouro,
        string $numero,
        string $complemento,
        string $bairro,
        string $cidade,
        string $estado
    ): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tutores (nome, cpf, telefone, endereco, cep, logradouro, numero, complemento, bairro, cidade, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nome, $cpf, $telefone, $endereco, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $estado]);
        return (int) $this->db->lastInsertId();
    }

    public function update(
        int $id,
        string $nome,
        string $cpf,
        string $telefone,
        string $endereco,
        string $cep,
        string $logradouro,
        string $numero,
        string $complemento,
        string $bairro,
        string $cidade,
        string $estado
    ): void
    {
        $stmt = $this->db->prepare(
            'UPDATE tutores SET nome = ?, cpf = ?, telefone = ?, endereco = ?, cep = ?, logradouro = ?, numero = ?,
             complemento = ?, bairro = ?, cidade = ?, estado = ? WHERE id = ?'
        );
        $stmt->execute([$nome, $cpf, $telefone, $endereco, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $estado, $id]);
    }
}