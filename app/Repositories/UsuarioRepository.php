<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Entities\Usuario;

class UsuarioRepository extends Repository
{
    protected string $tabela = 'usuarios';

    protected function paraEntidade(array $linha): Usuario
    {
        return Usuario::fromArray($linha);
    }

    /** @return Usuario[] */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT id, nome, email, perfil, ativo FROM usuarios ORDER BY nome'
        );

        return $this->paraEntidades($stmt->fetchAll());
    }

    public function findByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $linha = $stmt->fetch();

        return $linha ? $this->paraEntidade($linha) : null;
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

    public function create(Usuario $usuario): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)'
        );
        $dados = $usuario->toArray();
        $stmt->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha' => $dados['senha'],
            'perfil' => $dados['perfil'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(Usuario $usuario): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET nome = :nome, email = :email, perfil = :perfil WHERE id = :id'
        );
        $stmt->execute([
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'perfil' => $usuario->perfil,
            'id' => $usuario->id,
        ]);
    }

    public function setAtivo(Usuario $usuario, bool $ativo): void
    {
        $stmt = $this->db->prepare('UPDATE usuarios SET ativo = ? WHERE id = ?');
        $stmt->execute([$ativo ? 1 : 0, $usuario->id]);
    }
}
