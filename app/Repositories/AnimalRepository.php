<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Entities\Animal;

class AnimalRepository extends Repository
{
    protected string $tabela = 'animais';

    protected function paraEntidade(array $linha): Animal
    {
        return Animal::fromArray($linha);
    }

    /** @return Animal[] */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT animais.*, tutores.nome AS tutor_nome
             FROM animais
             JOIN tutores ON tutores.id = animais.tutor_id
             ORDER BY animais.nome'
        );
        return $this->paraEntidades($stmt->fetchAll());
    }

    public function create(Animal $animal): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO animais (tutor_id, nome, especie, raca, data_nascimento, peso)
             VALUES (:tutor_id, :nome, :especie, :raca, :data_nascimento, :peso)'
        );
        $stmt->execute($animal->toArray());
        return (int) $this->db->lastInsertId();
    }

    public function update(Animal $animal): void
    {
        $stmt = $this->db->prepare(
            'UPDATE animais SET tutor_id = :tutor_id, nome = :nome, especie = :especie, raca = :raca,
             data_nascimento = :data_nascimento, peso = :peso
             WHERE id = :id'
        );
        $stmt->execute($animal->toArray() + ['id' => $animal->id]);
    }
}
