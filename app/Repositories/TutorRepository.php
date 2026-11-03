<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Entities\Tutor;

class TutorRepository extends Repository
{
    protected string $tabela = 'tutores';

    protected function paraEntidade(array $linha): Tutor
    {
        return Tutor::fromArray($linha);
    }

    /** @return Tutor[] */
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM tutores ORDER BY nome');
        return $this->paraEntidades($stmt->fetchAll());
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

    public function create(Tutor $tutor): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tutores (nome, cpf, telefone, endereco, cep, logradouro, numero, complemento, bairro, cidade, estado)
             VALUES (:nome, :cpf, :telefone, :endereco, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado)'
        );
        $stmt->execute($tutor->toArray());
        return (int) $this->db->lastInsertId();
    }

    public function update(Tutor $tutor): void
    {
        $stmt = $this->db->prepare(
            'UPDATE tutores SET nome = :nome, cpf = :cpf, telefone = :telefone, endereco = :endereco, cep = :cep,
             logradouro = :logradouro, numero = :numero, complemento = :complemento, bairro = :bairro,
             cidade = :cidade, estado = :estado
             WHERE id = :id'
        );
        $stmt->execute($tutor->toArray() + ['id' => $tutor->id]);
    }
}
