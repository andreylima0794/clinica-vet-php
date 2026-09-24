<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT id, nome, email, perfil, ativo FROM usuarios ORDER BY nome');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        $sql = 'SELECT id FROM usuarios WHERE email = ?';
        $params = [$email];

        if ($exceptId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(string $nome, string $email, string $senhaHash, string $perfil): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nome, $email, $senhaHash, $perfil]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $nome, string $email, string $perfil): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET nome = ?, email = ?, perfil = ? WHERE id = ?'
        );
        $stmt->execute([$nome, $email, $perfil, $id]);
    }

    public function setAtivo(int $id, bool $ativo): void
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET ativo = ? WHERE id = ?');
        $stmt->execute([$ativo ? 1 : 0, $id]);
    }
}